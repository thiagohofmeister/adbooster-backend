<?php

namespace App\Service;

use App\Exception\ApiResponseException;
use App\Exception\Repository\DataNotFoundException;
use App\Exception\ValidationException;
use App\Enum;
use App\Model\Element;
use App\Model\Entity;
use App\Service\Base\Service\Contract;
use THS\Utils\Enum\HttpStatusCode;
use App\Service\Base\Repository;

/**
 * Serviço relacionado aos pedidos.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Order extends Contract
{
    /**
     * @var Repository\Announcement
     * @Inject
     */
    private $announcementRepository;

    /**
     * @var Repository\User
     * @Inject
     */
    private $userRepository;

    /**
     * @var \App\Model\Entity\User
     * @Inject
     */
    private $userLogged;

    /**
     * @var Repository\Order
     * @Inject
     */
    private $orderRepository;

    /**
     * Cria um pedido e aplica todas as regras necessárias.
     *
     * @return Base\Response
     *
     * @throws ApiResponseException
     * @throws ValidationException
     */
    public function create()
    {
        try {

            $body = $this->prepareBuildToSave($this->getRequest()->getParsedBody());

            $order = Entity\Order::fromArray($body);

            /** @var Element\Order\Item $item */
            foreach ($order->getItems() as $item) {

                $announcement = $this->announcementRepository->getById($item->getCode());

                if ($announcement->getStock() < $item->getQuantity()) {

                    throw new ApiResponseException('Não há estoque suficiente do produto.', HttpStatusCode::BAD_REQUEST());
                }

                $announcement->decreaseStock($item->getQuantity());

                if ($announcement->getStock() === 0) {
                    $announcement->setStatus(Enum\Announcement\Status::PAUSED());
                }

                $this->announcementRepository->save($announcement);

                $comissions = $this->getComissionsRecursive($announcement->getImpulses(), $item->getSeller());
                $item->setComissions($comissions);

                $comissionPrice = (float) ($item->getImpulsePrice() / sizeof($item->getComissions())) * $item->getQuantity();

                $this->applyComissions($item, $comissionPrice);

                $this->payAnnouncementOwner($item);
            }

            $this->customerPay($order);

            $this->orderRepository->save($order);

            return Base\Response::create($order->toArray(), HttpStatusCode::OK());

        } catch (ValidationException $exception) {

            throw $exception;

        } catch (\Throwable $throwable) {

            throw new ApiResponseException($throwable->getMessage(), HttpStatusCode::BAD_REQUEST());
        }
    }

    /**
     * Prepara os dados do body para poder ser construído.
     * Completa os dados do body com informações para poder criar um pedido.
     *
     * @param $body
     *
     * @return array
     */
    private function prepareBuildToSave($body)
    {
        $body['status'] = Enum\Order\Status::PENDING;

        $totalPrice = 0;
        foreach ($body['items'] as $item) {

            $product = Element\Order\Item::fromArray($item);

            $totalPrice += $product->getQuantity() * $product->getCurrentPrice();
        }

        $body['totalPrice'] = $totalPrice;

        return $body;
    }

    /**
     * Retorna os códigos dos usuários que precisam receber comissão.
     *
     * @param Element\Impulse[] $impulses
     * @param string $userCode
     *
     * @return array
     */
    private function getComissionsRecursive($impulses, string $userCode)
    {
        $comissions = [];

        foreach ($impulses as $impulse) {
            if ($impulse->getOwner() === $userCode) {

                if (!empty($impulse->getOrigin())) {
                    $comissions[] = $impulse->getOwner();

                    $comissions = array_merge($comissions, $this->getComissionsRecursive($impulses, $impulse->getOrigin()));
                }
            }
        }

        return $comissions;
    }

    /**
     * Aplica as comissões de quem impulsionou o anúncio.
     *
     * @param Element\Order\Item $item
     * @param float $comissionPrice
     *
     * @throws DataNotFoundException
     */
    private function applyComissions(Element\Order\Item $item, float $comissionPrice)
    {
        foreach ($item->getComissions() as $commission) {

            $user = $this->userRepository->getById($commission);
            $user->increaseCoins($comissionPrice);

            $this->userRepository->save($user);
        }
    }

    /**
     * Paga o dono do anúncio.
     *
     * @param Element\Order\Item $item
     *
     * @throws DataNotFoundException
     */
    private function payAnnouncementOwner(Element\Order\Item $item)
    {
        $announcement = $this->announcementRepository->getById($item->getCode());
        $owner = $this->userRepository->getById(reset($announcement->getImpulses())->getOwner());

        $priceUserOwnerGain = ($item->getCurrentPrice() * $item->getQuantity()) - ($item->getImpulsePrice() * $item->getQuantity());

        $this->userRepository->save($owner->increaseCoins($priceUserOwnerGain));
    }

    /**
     * Desconta do comprador o valor do pedido.
     *
     * @param Entity\Order $order
     */
    private function customerPay(Entity\Order $order)
    {
        $this->userLogged->decreaseCoins($order->getTotalPrice());
        $this->userRepository->save($this->userLogged);
    }
}
