<?php

namespace App\Model\Entity;

use App\Enum;
use App\Model\Element;
use THS\Utils\Date;

/**
 * Modelagem de pedido.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Order extends EntityAbstract
{
    /** @var Element\Order\Item[] */
    private $items;

    /** @var Element\User\Address */
    private $billingAddress;

    /** @var Element\User\Address */
    private $shippingAddress;

    /** @var float */
    private $totalPrice;

    /** @var string Código do comprador. */
    private $customer;

    /** @var Enum\Order\Status */
    private $status;

    /** @var \DateTime */
    private $created;

    /** @var \DateTime */
    private $updated;

    /**
     * Retorna a propriedade {@see Order::$items}.
     *
     * @return Element\Order\Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Define a propriedade {@see Order::$items}.
     *
     * @param Element\Order\Item[] $items
     *
     * @return static|Order
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Order::$billingAddress}.
     *
     * @return Element\User\Address
     */
    public function getBillingAddress(): Element\User\Address
    {
        return $this->billingAddress;
    }

    /**
     * Define a propriedade {@see Order::$billingAddress}.
     *
     * @param Element\User\Address $billingAddress
     *
     * @return static|Order
     */
    public function setBillingAddress(Element\User\Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Order::$shippingAddress}.
     *
     * @return Element\User\Address
     */
    public function getShippingAddress(): Element\User\Address
    {
        return $this->shippingAddress;
    }

    /**
     * Define a propriedade {@see Order::$shippingAddress}.
     *
     * @param Element\User\Address $shippingAddress
     *
     * @return static|Order
     */
    public function setShippingAddress(Element\User\Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Order::$totalPrice}.
     *
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * Define a propriedade {@see Order::$totalPrice}.
     *
     * @param float $totalPrice
     *
     * @return static|Order
     */
    public function setTotalPrice(float $totalPrice)
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Order::$customer}.
     *
     * @return string
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * Define a propriedade {@see Order::$customer}.
     *
     * @param string $customer
     *
     * @return static|Order
     */
    public function setCustomer(string $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Order::$status}.
     *
     * @return Enum\Order\Status
     */
    public function getStatus(): Enum\Order\Status
    {
        return $this->status;
    }

    /**
     * Define a propriedade {@see Order::$status}.
     *
     * @param Enum\Order\Status $status
     *
     * @return static|Order
     */
    public function setStatus(Enum\Order\Status $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Order::$created}.
     *
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * Define a propriedade {@see Order::$created}.
     *
     * @param \DateTime $created
     *
     * @return static|Order
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Order::$updated}.
     *
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * Define a propriedade {@see Order::$updated}.
     *
     * @param \DateTime $updated
     *
     * @return static|Order
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $items = [];
        foreach ($this->getItems() as $item) {

            $items[] = $item->toArray();
        }

        $toArray = [
            'items' => $items,
            'billingAddress' => !empty($this->getBillingAddress()) ? $this->getBillingAddress()->toArray() : null,
            'shippingAddress' => !empty($this->getShippingAddress()) ? $this->getShippingAddress()->toArray() : null,
            'totalPrice' => $this->getTotalPrice(),
            'customer' => $this->getCustomer(),
            'status' => $this->getStatus()->value(),
            'created' => $this->getCreated()->format(Date::JAVASCRIPT_ISO_FORMAT),
            'updated' => $this->getUpdated()->format(Date::JAVASCRIPT_ISO_FORMAT),
        ];

        if (!empty($this->getId())) {
            $toArray['_id'] = $this->getId();
        }

        return $toArray;
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        $items = [];
        foreach ($array['items'] as $item) {

            $items[] = Element\Order\Item::fromArray((array) $item);
        }

        return (new static($array['_id']))
            ->setItems($items)
            ->setBillingAddress(!empty((array) $array['billingAddress']) ? Element\User\Address::fromArray((array) $array['billingAddress']) : null)
            ->setShippingAddress(!empty((array) $array['shippingAddress']) ? Element\User\Address::fromArray((array) $array['shippingAddress']) : null)
            ->setTotalPrice($array['totalPrice'])
            ->setCustomer($array['customer'])
            ->setStatus(Enum\Order\Status::memberByValue($array['status']))
            ->setCreated(new \DateTime($array['created']))
            ->setUpdated(new \DateTime($array['updated']));
    }
}
