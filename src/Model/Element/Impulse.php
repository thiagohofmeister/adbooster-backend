<?php

namespace App\Model\Element;

use App\Model\Element;

/**
 * Elemento de impulso.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Impulse extends ElementAbstract
{
    /** @var Element\User\Creator Usuário que fez o impulso. */
    private $owner;

    /** @var Element\User\Creator Usuário que fez o impulso anterior. */
    private $origin;

    /**
     * Retorna a propriedade {@see Impulse::$owner}.
     *
     * @return User\Creator
     */
    public function getOwner(): User\Creator
    {
        return $this->owner;
    }

    /**
     * Define a propriedade {@see Impulse::$owner}.
     *
     * @param User\Creator $owner
     *
     * @return static|Impulse
     */
    public function setOwner(User\Creator $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Impulse::$origin}.
     *
     * @return User\Creator
     */
    public function getOrigin(): ?User\Creator
    {
        return $this->origin;
    }

    /**
     * Define a propriedade {@see Impulse::$origin}.
     *
     * @param User\Creator $origin
     *
     * @return static|Impulse
     */
    public function setOrigin(?User\Creator $origin)
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'owner' => $this->getOwner()->toArray(),
            'origin' => !empty($this->getOrigin()) ? $this->getOrigin()->toArray() : null,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setOwner(Element\User\Creator::fromArray((array) $array['owner']))
            ->setOrigin(!empty((array) $array['origin']) ? Element\User\Creator::fromArray((array) $array['origin']) : null);
    }
}
