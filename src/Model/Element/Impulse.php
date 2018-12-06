<?php

namespace App\Model\Element;

use THS\Utils\Date;

/**
 * Elemento de impulso.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Impulse extends ElementAbstract
{
    /** @var string Usuário que fez o impulso. */
    private $owner;

    /** @var string Usuário que fez o impulso anterior. */
    private $origin;

    /** @var \DateTime Data do impulso. */
    private $created;

    /**
     * Retorna a propriedade {@see Impulse::$owner}.
     *
     * @return string
     */
    public function getOwner(): ?string
    {
        return $this->owner;
    }

    /**
     * Define a propriedade {@see Impulse::$owner}.
     *
     * @param string $owner
     *
     * @return static|Impulse
     */
    public function setOwner(?string $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Impulse::$origin}.
     *
     * @return string
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * Define a propriedade {@see Impulse::$origin}.
     *
     * @param string $origin
     *
     * @return static|Impulse
     */
    public function setOrigin(?string $origin)
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Impulse::$created}.
     *
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * Define a propriedade {@see Impulse::$created}.
     *
     * @param \DateTime $created
     *
     * @return static|Impulse
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'owner' => $this->getOwner(),
            'origin' => $this->getOrigin(),
            'created' => $this->getCreated()->format(Date::JAVASCRIPT_ISO_FORMAT),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setOwner($array['owner'])
            ->setOrigin($array['origin'])
            ->setCreated(new \DateTime($array['created']));
    }
}
