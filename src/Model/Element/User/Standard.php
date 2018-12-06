<?php

namespace App\Model\Element\User;

use App\Model\Element\ElementAbstract;

/**
 * Elemento de usuário padrão.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Standard extends ElementAbstract
{
    /** @var string */
    protected $code;

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
     * Converte o elemento para um array esperado pelo documento.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
        ];
    }

    /**
     * Cria um elemento a partir dos dados do documento.
     *
     * @param array $array
     *
     * @return static|ElementAbstract
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setCode($array['code']);
    }
}