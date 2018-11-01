<?php

namespace App\Service\Base\Repository;

use App\Exception\Repository\DataNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Database;
use THS\Utils\Date;
use App\Model\Entity;

/**
 * Repositório de operações de banco da coleção de anúncios.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Announcement extends AbstractRepository
{
    /**
     * @param Database $database
     * @Inject
     */
    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    public function getByUser(string $userCode) {

        $query = [];

        $query[] = [
            '$unwind' => [
                'path' => '$impulses'
            ]
        ];

        $query[] = [
            '$match' => [
                'impulses.owner.code' => $userCode,
            ]
        ];

        $query[] = [
            '$group' => [
                '_id' => '$_id'
            ]
        ];

        $documents = $this->collection->aggregate($query);

        $ids = [];
        foreach ($documents as $document) {
            $ids[] = new ObjectId((string) $document['_id']);
        }

        $query = [
            '_id' => ['$in' => $ids]
        ];

        return $this->find($query);
    }

    /**
     * @inheritDoc
     *
     * @return Entity\User
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

        return Entity\Announcement::fromArray((array) $document);
    }

    /**
     * @inheritDoc
     *
     * @param Entity\User $user
     */
    protected function toDocument($user)
    {
        $array = $user->toArray();

        $array['date'] = new UTCDateTime($user->getDate());

        if (!empty($array['authentication'])) {
            $array['authentication']['expires'] = new UTCDateTime($user->getAuthentication()->getExpires());
        }

        return $array;
    }
}
