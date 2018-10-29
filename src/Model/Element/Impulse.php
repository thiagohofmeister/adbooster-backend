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
    /** @var Element\User\Standard Usuário que fez o impulso. */
    private $owner;

    /** @var Element\User\Standard Usuário que fez o impulso anterior. */
    private $origin;

    /**
     * Retorna a propriedade {@see Impulse::$owner}.
     *
     * @return User\Standard
     */
    public function getOwner(): User\Standard
    {
        return $this->owner;
    }

    /**
     * Define a propriedade {@see Impulse::$owner}.
     *
     * @param User\Standard $owner
     *
     * @return static|Impulse
     */
    public function setOwner(User\Standard $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Impulse::$origin}.
     *
     * @return User\Standard
     */
    public function getOrigin(): ?User\Standard
    {
        return $this->origin;
    }

    /**
     * Define a propriedade {@see Impulse::$origin}.
     *
     * @param User\Standard $origin
     *
     * @return static|Impulse
     */
    public function setOrigin(?User\Standard $origin)
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
            'origin' => $this->getOrigin()->toArray(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setOwner(Element\User\Standard::fromArray((array) $array['owner']))
            ->setOrigin(Element\User\Standard::fromArray((array) $array['origin']));
    }
}
