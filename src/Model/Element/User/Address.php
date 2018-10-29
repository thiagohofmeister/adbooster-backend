<?php

namespace App\Model\Element\User;

use App\Model\Element\ElementAbstract;

/**
 * Elemento de endereço do cliente.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Address extends ElementAbstract
{
    /** @var string */
    private $state;

    /** @var string */
    private $city;

    /** @var string */
    private $district;

    /** @var string */
    private $street;

    /** @var int */
    private $number;

    /** @var string */
    private $reference;

    /** @var string */
    private $zipCode;

    /**
     * Retorna a propriedade {@see Address::$state}.
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Define a propriedade {@see Address::$state}.
     *
     * @param string $state
     *
     * @return static|Address
     */
    public function setState(string $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Address::$city}.
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Define a propriedade {@see Address::$city}.
     *
     * @param string $city
     *
     * @return static|Address
     */
    public function setCity(string $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Address::$district}.
     *
     * @return string
     */
    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * Define a propriedade {@see Address::$district}.
     *
     * @param string $district
     *
     * @return static|Address
     */
    public function setDistrict(string $district)
    {
        $this->district = $district;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Address::$street}.
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Define a propriedade {@see Address::$street}.
     *
     * @param string $street
     *
     * @return static|Address
     */
    public function setStreet(string $street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Address::$number}.
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Define a propriedade {@see Address::$number}.
     *
     * @param int $number
     *
     * @return static|Address
     */
    public function setNumber(int $number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Address::$reference}.
     *
     * @return string
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * Define a propriedade {@see Address::$reference}.
     *
     * @param string $reference
     *
     * @return static|Address
     */
    public function setReference(?string $reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Address::$zipCode}.
     *
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * Define a propriedade {@see Address::$zipCode}.
     *
     * @param string $zipCode
     *
     * @return static|Address
     */
    public function setZipCode(string $zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'state' => $this->getState(),
            'city' => $this->getCity(),
            'district' => $this->getDistrict(),
            'street' => $this->getStreet(),
            'number' => $this->getNumber(),
            'reference' => $this->getReference(),
            'zipCode' => $this->getZipCode(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setState($array['state'])
            ->setCity($array['city'])
            ->setDistrict($array['district'])
            ->setStreet($array['street'])
            ->setNumber($array['number'])
            ->setReference($array['reference'])
            ->setZipCode($array['zipCode']);
    }
}
