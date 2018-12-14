<?php

namespace App\Service\Base\Repository;

use App\Exception\Repository\DataNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Database;
use THS\Utils\Date;
use App\Model\Entity;

/**
 * Repositório de operações de banco da coleção de pedidos.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Order extends AbstractRepository
{
    /**
     * @param Database $database
     * @Inject
     */
    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    /**
     * Retorna um pedido pelo id.
     *
     * @param string $code
     *
     * @return Entity\Order
     *
     * @throws DataNotFoundException
     */
    public function getById(string $code): Entity\Order
    {
        return $this->findOne(['_id' => new ObjectId($code)]);
    }

    /**
     * Retorna um pedido pelo cliente.
     *
     * @param string $customerCode
     *
     * @return Entity\Order[]
     *
     * @throws \Exception
     */
    public function getByCustomer(string $customerCode)
    {
        $options = [
            'skip' => $this->getOffset(),
            'limit' => $this->getLimit(),
            'sort' => ['created' => -1]
        ];

        return $this->find(['customer' => $customerCode], $options);
    }

    /**
     * Retorna um pedido pelo vendedor.
     *
     * @param string $sellerCode
     *
     * @return Entity\Order[]
     *
     * @throws \Exception
     */
    public function getBySeller(string $sellerCode)
    {
        $options = [
            'skip' => $this->getOffset(),
            'limit' => $this->getLimit(),
            'sort' => ['created' => -1]
        ];

        return $this->find(['items.seller' => $sellerCode], $options);
    }

    /**
     * @inheritDoc
     *
     * @return Entity\Order
     */
    protected function fromDocument($document)
    {
        if (empty($document)) {
            return null;
        }

        $document['created'] = $document['created']->toDateTime()
            ->format(Date::JAVASCRIPT_ISO_FORMAT);

        $document['updated'] = $document['updated']->toDateTime()
            ->format(Date::JAVASCRIPT_ISO_FORMAT);

        return Entity\Order::fromArray((array) $document);
    }

    /**
     * @inheritDoc
     *
     * @param Entity\Order $entity
     */
    protected function toDocument($entity)
    {
        $array = $entity->toArray();

        $array['created'] = new UTCDateTime($entity->getCreated());
        $array['updated'] = new UTCDateTime($entity->getUpdated());

        return $array;
    }
}
