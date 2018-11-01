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

    public function getByUserAndFriends(string $userCode, $friends = []) {

        $query = [];

        $query[] = [
            '$unwind' => [
                'path' => '$impulses'
            ]
        ];

        $query[] = [
            '$match' => [
                '$or' => [
                    ['impulses.owner.code' => $userCode],
                    ['impulses.owner.code' => ['$in' => $friends]]
                ]
            ]
        ];

        $query[] = [
            '$sort' => [
                'updated' => -1
            ]
        ];

        if ($this->isPaginated()) {

            $queryCount = array_merge($query, [
                [
                    '$count' => 'total'
                ]
            ]);

            $this->setPaginationTotal(reset($this->collection->aggregate($queryCount)->toArray())['total']);
        }

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

        return $this->find([
            '_id' => ['$in' => $ids]
        ], [
            'limit' => $this->getLimit(),
            'skip' => $this->getOffset(),
            'sort' => ['updated' => -1]
        ]);
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
