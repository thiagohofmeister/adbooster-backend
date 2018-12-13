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
     * @return Entity\Announcement
     *
     * @throws DataNotFoundException
     */
    public function getById(string $code): Entity\Announcement
    {
        return $this->findOne(['_id' => new ObjectId($code)]);
    }

    /**
     * @inheritDoc
     *
     * @return Entity\Announcement
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
     * @param Entity\Announcement $entity
     */
    protected function toDocument($entity)
    {
        $array = $entity->toArray();

        $array['created'] = new UTCDateTime($entity->getCreated());
        $array['updated'] = new UTCDateTime($entity->getUpdated());

        return $array;
    }
}
