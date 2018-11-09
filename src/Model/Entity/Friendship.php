<?php

namespace App\Model\Entity;

use App\Model\Element;
use THS\Utils\Date;

/**
 * Modelagem de amizade.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Friendship extends EntityAbstract
{
    /** @var string Código do usuário que fez o pedido de amizade. */
    private $userAdd;

    /** @var string Código do usuário que aceitou o pedido de amizade. */
    private $userAdded;

    /** @var bool */
    private $confirmed;

    /** @var \DateTime Data do início da amizade. */
    private $start;

    /**
     * Retorna a propriedade {@see Friendship::$userAdd}.
     *
     * @return string
     */
    public function getUserAdd(): string
    {
        return $this->userAdd;
    }

    /**
     * Define a propriedade {@see Friendship::$userAdd}.
     *
     * @param string $userAdd
     *
     * @return static|Friendship
     */
    public function setUserAdd(string $userAdd)
    {
        $this->userAdd = $userAdd;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Friendship::$userAdded}.
     *
     * @return string
     */
    public function getUserAdded(): string
    {
        return $this->userAdded;
    }

    /**
     * Define a propriedade {@see Friendship::$userAdded}.
     *
     * @param string $userAdded
     *
     * @return static|Friendship
     */
    public function setUserAdded(string $userAdded)
    {
        $this->userAdded = $userAdded;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Friendship::$confirmed}.
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * Define a propriedade {@see Friendship::$confirmed}.
     *
     * @param bool $confirmed
     *
     * @return static|Friendship
     */
    public function setConfirmed(bool $confirmed)
    {
        $this->confirmed = $confirmed;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Friendship::$start}.
     *
     * @return \DateTime
     */
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    /**
     * Define a propriedade {@see Friendship::$start}.
     *
     * @param \DateTime $start
     *
     * @return static|Friendship
     */
    public function setStart(?\DateTime $start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'userAdd' => $this->getUserAdd(),
            'userAdded' => $this->getUserAdded(),
            'confirmed' => $this->isConfirmed(),
            'start' => $this->getStart()->format(Date::JAVASCRIPT_ISO_FORMAT),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setUserAdd($array['userAdd'])
            ->setUserAdded($array['userAdded'])
            ->setConfirmed($array['confirmed'])
            ->setStart(!empty($array['start']) ? new \DateTime($array['start']) : null);
    }
}
