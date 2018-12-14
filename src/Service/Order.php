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
     * Retorna todos os pedidos do usuário logado.
     *
     * @return Base\Response
     *
     * @throws DataNotFoundException
     */
    public function index()
    {
        $total = 0;

        try {

            $page = $this->getRequest()->getQueryParam('page') ?: 1;
            $limit = $this->getRequest()->getQueryParam('limit') ?: 0;

            $orders = $this->orderRepository
                ->setPaginated($page, $limit)
                ->getByCustomer((string) $this->userLogged->getId());

            $total = $this->orderRepository->getPaginationTotal();

        } catch (\Throwable $throwable) {

            $orders = [];
        }

        $ordersFormatted = [];
        foreach ($orders as $order) {

            $orderFormatted = $this->formatOrder($order);

            $ordersFormatted[] = $orderFormatted;
        }

        return Base\Response::create([
            'total' => $total,
            'items' => $ordersFormatted
        ], HttpStatusCode::OK());
    }

    /**
     * Retorna todos os pedidos de um vendedor.
     *
     * @return Base\Response
     *
     * @throws DataNotFoundException
     */
    public function retrieveBySeller()
    {
        $total = 0;

        try {

            $sellerCode = $this->getRequest()->getQueryParam('sellerCode');
            $page = $this->getRequest()->getQueryParam('page') ?: 1;
            $limit = $this->getRequest()->getQueryParam('limit') ?: 0;

            $orders = $this->orderRepository
                ->setPaginated($page, $limit)
                ->getBySeller($sellerCode);

            $total = $this->orderRepository->getPaginationTotal();

        } catch (\Throwable $throwable) {

            $orders = [];
        }

        $ordersFormatted = [];
        foreach ($orders as $order) {

            $orderFormatted = $this->formatOrder($order);

            $ordersFormatted[] = $orderFormatted;
        }

        return Base\Response::create([
            'total' => $total,
            'items' => $ordersFormatted
        ], HttpStatusCode::OK());
    }

    /**
     * Retorna um único pedido por código.
     *
     * @param string $code
     *
     * @return Base\Response
     */
    public function retrieve(string $code)
    {
        try {

            $order = $this->orderRepository
                ->getById($code);

            $orderFormatted = $this->formatOrder($order);

            return Base\Response::create($orderFormatted, HttpStatusCode::OK());

        } catch (\Throwable $throwable) {

            return Base\Response::create(['message' => 'Pedido não encontrado.'], HttpStatusCode::NOT_FOUND());
        }
    }

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

                $comissions = $this->getComissionsRecursive($announcement->getImpulses(), $item->getImpulsedBy());
                $item->setComissions($comissions);

                $comissionPrice = (float) ($item->getImpulsePrice() / sizeof($item->getComissions())) * $item->getQuantity();

                $this->applyComissions($item, $comissionPrice);

                $this->payAnnouncementOwner($item);
            }

            $this->customerPay($order);

            $this->orderRepository->save($order);

            return Base\Response::create($this->formatOrder($order), HttpStatusCode::OK());

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

    /**
     * Retorna pedido formatado para retornar na API.
     *
     * @param Entity\Order $order
     *
     * @return array
     *
     * @throws DataNotFoundException
     */
    private function formatOrder(Entity\Order $order)
    {
        $orderFormatted = $order->toArray();

        foreach ($orderFormatted['items'] as &$item) {

            $announcement = $this->announcementRepository->getById($item['code']);

            $item['title'] = $announcement->getTitle();
            $item['images'] = $announcement->getImages();
        }

        return $orderFormatted;
    }
}
