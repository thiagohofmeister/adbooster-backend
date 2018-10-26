<?php

namespace App\Model\Element\User;

use THS\Utils\Date;
use App\Model\Element\ElementAbstract;

/**
 * Dados de autenticação do usuário.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Authentication extends ElementAbstract
{
    /** @var string Token. */
    private $token;

    /** @var \DateTime Data de expiração. */
    private $expires;

    /**
     * Retorna a propriedade {@see Authentication::$token}.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Define a propriedade {@see Authentication::$token}.
     *
     * @param string $token
     *
     * @return static|Authentication
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Retorna a propriedade {@see Authentication::$expires}.
     *
     * @return \DateTime
     */
    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * Define a propriedade {@see Authentication::$expires}.
     *
     * @param \DateTime $expires
     *
     * @return static|Authentication
     */
    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'token' => $this->getToken(),
            'expires' => $this->getExpires()->format(Date::JAVASCRIPT_ISO_FORMAT),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setToken($array['token'])
            ->setExpires(new \DateTime($array['expires']));
    }
}
