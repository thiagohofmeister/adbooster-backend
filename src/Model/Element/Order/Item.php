<?php

namespace App\Model\Element\Order;

use App\Model\Element\ElementAbstract;

/**
 * @todo Document class Item.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Item extends ElementAbstract
{
    /** @var string Código do anúncio no banco. */
    private $code;

    /** @var float */
    private $previousPrice;

    /** @var float */
    private $currentPrice;

    /** @var int */
    private $quantity;

    /** @var string Código do vendedor. */
    private $seller;

    /** @var array Códigos de todos que receberam dinheiro pelo impulso. */
    private $comissions;

    /** @var float Valor pago pelos impulsos. */
    private $impulsePrice;

    /**
     * Retorna a propriedade {@see Item::$code}.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Define a propriedade {@see Item::$code}.
     *
     * @param string $code
     *
     * @return static|Item
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Item::$previousPrice}.
     *
     * @return float
     */
    public function getPreviousPrice(): float
    {
        return $this->previousPrice;
    }

    /**
     * Define a propriedade {@see Item::$previousPrice}.
     *
     * @param float $previousPrice
     *
     * @return static|Item
     */
    public function setPreviousPrice(float $previousPrice)
    {
        $this->previousPrice = $previousPrice;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Item::$currentPrice}.
     *
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    /**
     * Define a propriedade {@see Item::$currentPrice}.
     *
     * @param float $currentPrice
     *
     * @return static|Item
     */
    public function setCurrentPrice(float $currentPrice)
    {
        $this->currentPrice = $currentPrice;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Item::$quantity}.
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Define a propriedade {@see Item::$quantity}.
     *
     * @param int $quantity
     *
     * @return static|Item
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Item::$seller}.
     *
     * @return string
     */
    public function getSeller(): string
    {
        return $this->seller;
    }

    /**
     * Define a propriedade {@see Item::$seller}.
     *
     * @param string $seller
     *
     * @return static|Item
     */
    public function setSeller(string $seller)
    {
        $this->seller = $seller;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Item::$comissions}.
     *
     * @return array
     */
    public function getComissions(): ?array
    {
        return $this->comissions;
    }

    /**
     * Define a propriedade {@see Item::$comissions}.
     *
     * @param array $comissions
     *
     * @return static|Item
     */
    public function setComissions(?array $comissions)
    {
        $this->comissions = $comissions;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Item::$impulsePrice}.
     *
     * @return float
     */
    public function getImpulsePrice(): ?float
    {
        return $this->impulsePrice;
    }

    /**
     * Define a propriedade {@see Item::$impulsePrice}.
     *
     * @param float $impulsePrice
     *
     * @return static|Item
     */
    public function setImpulsePrice(?float $impulsePrice)
    {
        $this->impulsePrice = $impulsePrice;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'previousPrice' => $this->getPreviousPrice(),
            'currentPrice' => $this->getCurrentPrice(),
            'quantity' => $this->getQuantity(),
            'seller' => $this->getSeller(),
            'comissions' => $this->getComissions(),
            'impulsePrice' => $this->getImpulsePrice(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setCode($array['code'])
            ->setPreviousPrice($array['previousPrice'])
            ->setCurrentPrice($array['currentPrice'])
            ->setQuantity($array['quantity'])
            ->setSeller($array['seller'])
            ->setComissions(!empty($array['comissions']) ? $array['comissions'] : null)
            ->setImpulsePrice(!empty($array['impulsePrice']) ? $array['impulsePrice'] : null);
    }
}
