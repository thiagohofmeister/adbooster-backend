<?php

namespace App\Model\Entity;

use THS\Utils\Date;
use App\Model\Element;

/**
 * Representa a modelagem dos usuários do sistema.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class User extends EntityAbstract
{
    /** @var string Email. */
    private $email;

    /** @var string */
    private $password;

    /** @var Element\User\Authentication Autenticação.  */
    private $authentication;

    /** @var \DateTime Data de cadastro. */
    private $date;

    /**
     * Retorna a propriedade {@see User::$email}.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Define a propriedade {@see User::$email}.
     *
     * @param string $email
     *
     * @return static|User
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$authentication}.
     *
     * @return Element\User\Authentication
     */
    public function getAuthentication(): ?Element\User\Authentication
    {
        return $this->authentication;
    }

    /**
     * Define a propriedade {@see User::$authentication}.
     *
     * @param Element\User\Authentication $authentication
     *
     * @return static|User
     */
    public function setAuthentication(?Element\User\Authentication $authentication)
    {
        $this->authentication = $authentication;

        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$date}.
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        if (empty($this->date)) {
            $this->date = new \DateTime();
        }
        return $this->date;
    }

    /**
     * Define a propriedade {@see User::$date}.
     *
     * @param \DateTime $date
     *
     * @return static|User
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$password}.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Define a propriedade {@see User::$password}.
     *
     * @param string $password
     *
     * @return static|User
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $toArray = [
            'email' => $this->getEmail(),
            'authentication' => !empty($this->getAuthentication()) ? $this->getAuthentication()->toArray() : null,
            'date' => $this->getDate()->format(Date::JAVASCRIPT_ISO_FORMAT),
            'password' => $this->getPassword()
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
        return (new static($array['_id']))
            ->setEmail($array['email'])
            ->setAuthentication(!empty((array) $array['authentication']) ? Element\User\Authentication::fromArray((array) $array['authentication']) : null)
            ->setDate(new \DateTime($array['date']))
            ->setPassword($array['password']);
    }
}
