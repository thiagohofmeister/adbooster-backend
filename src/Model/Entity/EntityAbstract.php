<?php

namespace App\Model\Entity;

use MongoDB\BSON\ObjectId;

/**
 * Entidade básica que contempla os atributos e funções que todas entidades utilizam
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
abstract class EntityAbstract
{
    /** @var ObjectId Id da tabela da model na plataforma Moovin */
    protected $id;

    /**
     * @param ObjectId $id Identificador da entidade no banco de dados da Moovin
     */
    public function __construct(ObjectId $id = null)
    {
        $this->id = $id;
    }

    /**
     * Id da tabela da entidade no banco de dados da Moovin
     *
     * @return ObjectId
     */
    public function getId(): ?ObjectId
    {
        return $this->id;
    }

    /**
     * Id da tabela da entidade no banco de dados da Moovin
     *
     * @param ObjectId $id Id da entidade
     *
     * @return static|EntityAbstract
     */
    public function setId(ObjectId $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Converte a entidade para um array esperado pelo documento.
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * Cria uma entidade a partir dos dados do documento.
     *
     * @param array $array
     *
     * @return static|EntityAbstract
     */
    abstract public static function fromArray(array $array);
}
