<?php

namespace App\Model\Element\User;

use App\Model\Element\ElementAbstract;

/**
 * Elemento de usuário padrão.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Standard extends ElementAbstract
{
    /** @var string */
    private $email;

    /** @var string */
    private $documentNumber;

    /**
     * Retorna a propriedade {@see Standard::$email}.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Define a propriedade {@see Standard::$email}.
     *
     * @param string $email
     *
     * @return static|Standard
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Standard::$documentNumber}.
     *
     * @return string
     */
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    /**
     * Define a propriedade {@see Standard::$documentNumber}.
     *
     * @param string $documentNumber
     *
     * @return static|Standard
     */
    public function setDocumentNumber(string $documentNumber)
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'documentNumber' => $this->getDocumentNumber(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setEmail($array['email'])
            ->setDocumentNumber($array['documentNumber']);
    }
}
