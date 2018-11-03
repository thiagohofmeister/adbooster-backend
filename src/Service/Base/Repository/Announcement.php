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



        $documents = $this->collection->aggregate($query);

        $announcements = [];
        foreach ($documents as $document) {

            $document['impulses'] = [$document['impulses']];

            $announcements[] = $this->fromDocument($document);
        }

        return $announcements;
    }

    /**
     * Preenche os impulsos do anúncio.
     *
     * @param Entity\Announcement $announcement
     *
     * @return Entity\Announcement
     *
     * @throws DataNotFoundException
     */
    public function fillImpulses(Entity\Announcement $announcement) {

        $document = $this->findOne(['_id' => $announcement->getId()]);

        $announcement->setImpulses($document->getImpulses());

        return $announcement;
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

        return Entity\Announcement::fromArray((array) $document);
    }

    /**
     * @inheritDoc
     *
     * @param Entity\Announcement $announcement
     */
    protected function toDocument($announcement)
    {
        $array = $announcement->toArray();

        $array['created'] = new UTCDateTime($announcement->getCreated());
        $array['updated'] = new UTCDateTime($announcement->getUpdated());

        return $array;
    }
}
