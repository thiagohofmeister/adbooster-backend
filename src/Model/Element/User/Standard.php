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
    private $name;

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
     * Retorna a propriedade {@see Standard::$name}.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Define a propriedade {@see Standard::$name}.
     *
     * @param string $name
     *
     * @return static|Standard
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return (new static)
            ->setCode($array['code'])
            ->setName($array['name']);
    }
}
