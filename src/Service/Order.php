<?php

namespace App\Service;

use App\Exception\ApiResponseException;
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
     * @var Repository\Order
     * @Inject
     */
    private $orderRepository;

    public function create()
    {
        try {

            $body = $this->prepareBuildToSave($this->getRequest()->getParsedBody());

            $order = Entity\Order::fromArray($body);

            foreach ($order->getItems() as $item) {

                $announcement = $this->announcementRepository->getById($item->getCode());

                $commissions = $this->getComissionsRecursive($announcement->getImpulses(), $item->getSeller());

                $item->setComissions($commissions);
            }

            ~rt($order->toArray());

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
}
