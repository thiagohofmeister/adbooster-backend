<?php

namespace App\Model\Element;

/**
 * Elemento de impulso.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Impulse extends ElementAbstract
{
    /** @var string Usuário que fez o impulso. */
    private $owner;

    /** @var string Usuário que fez o impulso anterior. */
    private $origin;

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
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'owner' => $this->getOwner(),
            'origin' => $this->getOrigin(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setOwner($array['owner'])
            ->setOrigin($array['origin']);
    }
}
