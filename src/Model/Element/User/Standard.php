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
    private $code;

    /** @var string */
    private $nome;

    /**
     * Retorna a propriedade {@see Standard::$code}.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Define a propriedade {@see Standard::$code}.
     *
     * @param string $code
     *
     * @return static|Standard
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Standard::$nome}.
     *
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Define a propriedade {@see Standard::$nome}.
     *
     * @param string $nome
     *
     * @return static|Standard
     */
    public function setNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'nome' => $this->getNome(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setCode($array['code'])
            ->setNome($array['nome']);
    }
}
