<?php

namespace App\Service\Base\Repository;

use App\Exception\Repository\DataNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;
use MongoDB\Database;
use App\Model\Entity;

/**
 * Abstração dos repositórios.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
abstract class AbstractRepository
{
    /** @var Database */
    private $database;

    /** @var Collection */
    protected $collection;

    /** @var int Número da página atual. */
    private $page;

    /** @var int Quantidade de itens por página. */
    private $limit;

    /** @var int Offset da consulta da paginação. */
    private $offset;

    /** @var int Total de itens retornando da paginação. */
    private $total;

    /**
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;

        $this->collection = $this->database->selectCollection($this->getCollectionName());
        $this->clearPagination();
    }

    /**
     * Retorna o nome da coleção do relacionada a repository.
     *
     * @return string
     */
    protected function getCollectionName(): string
    {
        return str_replace('\\', '_', strtolower(str_replace(['App\\Service\\', 'Base\\', 'Repository\\'], '', static::class)));
    }

    /**
     * Salva a entidade no banco.
     *
     * @param Entity\EntityAbstract $entity
     */
    public function save($entity)
    {
        if (empty($entity->getId())) {
            $entity->setId(new ObjectId());
        }

        $dataToSave = $this->toDocument($entity);
        $updateResult = $this->collection->updateOne(['_id' => $entity->getId()], ['$set' => $dataToSave], ['upsert' => true]);

        if (!empty($updateResult->getUpsertedId())) {
            $entity->setId($updateResult->getUpsertedId());
        }
    }

    /**
     * Formata dados da entidade para estrutura do Banco de dados.
     *
     * @param Entity\EntityAbstract $entity
     *
     * @return array
     */
    protected function toDocument($entity)
    {
        return $entity->toArray();
    }

    /**
     * Formata o documento que foi buscado do Banco de dados.
     *
     * @param array $document
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function fromDocument($document)
    {
        throw new \Exception('Method "fromDocument" not implemented.');
    }

    /**
     * Prepara variáveis de paginação.
     *
     * @param int $page
     * @param int $limit
     *
     * @return AbstractRepository|static
     */
    public function setPaginated(int $page, int $limit)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->offset = $limit * ($page - 1);

        return $this;
    }

    /**
     * Valida se a requisição foi feita com paginação.
     *
     * @return bool
     */
    protected function isPaginated(): bool
    {
        return $this->page || $this->limit || $this->offset;
    }

    /**
     * @param int $total
     *
     * @return AbstractRepository|static
     */
    protected function setPaginationTotal(int $total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Retorna o total de itens da paginação.
     *
     * @return int
     */
    public function getPaginationTotal(): ?int
    {
        return $this->total;
    }

    /**
     * Limpa os dados da paginação.
     *
     * @return AbstractRepository|static
     */
    protected function clearPagination()
    {
        $this->page = 0;
        $this->limit = 0;
        $this->offset = 0;

        return $this;
    }

    /**
     * Retorna a propriedade {@see AbstractRepository::$limit}.
     *
     * @return int
     */
    protected function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Retorna a propriedade {@see AbstractRepository::$offset}.
     *
     * @return int
     */
    protected function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Método de busca abstrato.
     *
     * @param array $query
     * @param array $options
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function find($query = [], $options = [])
    {
        $documents = $this->collection->find($query, $options)->toArray();

        $array = [];
        foreach ($documents as $document) {
            $array[] = $this->fromDocument($document);
        }

        return $array;
    }

    /**
     * Método de busca abstrato de um único documento.
     *
     * @param array $query
     * @param array $options
     *
     * @return mixed
     *
     * @throws DataNotFoundException
     * @throws \Exception
     */
    protected function findOne(array $query = [], array $options = [])
    {
        $document = $this->collection->findOne($query, $options);

        if (empty($document)) {
            throw new DataNotFoundException($query);
        }

        return $this->fromDocument($document);
    }
}
