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

    /**
     * Retorna um anúncio pelo id.
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
     * Retorna os anúncios a partir de um usuário e seus amigos.
     *
     * @param string $userCode
     * @param array $friends
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getByUserAndFriends(string $userCode, $friends = [])
    {

        $query = [];

        $query[] = [
            '$unwind' => [
                'path' => '$impulses'
            ]
        ];

        $query[] = [
            '$match' => [
                '$or' => [
                    ['impulses.owner' => $userCode],
                    ['impulses.owner' => ['$in' => $friends]]
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

        if (!empty($this->getOffset())) {
            $query[] = [
                '$skip' => $this->getOffset()
            ];
        }

        if (!empty($this->getLimit())) {
            $query[] = [
                '$limit' => $this->getLimit()
            ];
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
     * Retorna os anúncios a partir de um usuário e seus amigos.
     *
     * @param string $search
     * @param array $userCodes
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getBySearchAndUsers(string $search, $userCodes = [])
    {
        $query = [];

        $query[] = [
            '$unwind' => [
                'path' => '$impulses'
            ]
        ];

        $query[] = [
            '$match' => [
                'impulses.owner' => ['$in' => $userCodes]
            ]
        ];

        $query[] = [
            '$match' => [
                '$or' => [
                    ['title' => ['$regex' => $search, '$options' => 'gi']],
                    ['description' => ['$regex' => $search, '$options' => 'gi']],
                ],
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

        if (!empty($this->getOffset())) {
            $query[] = [
                '$skip' => $this->getOffset()
            ];
        }

        if (!empty($this->getLimit())) {
            $query[] = [
                '$limit' => $this->getLimit()
            ];
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
    public function fillImpulses(Entity\Announcement $announcement)
    {

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

        foreach ($document['impulses'] as &$impulse) {

            $impulse['created'] = $impulse['created']->toDateTime()
                ->format(Date::JAVASCRIPT_ISO_FORMAT);
        }

        return Entity\Announcement::fromArray((array) $document);
    }

    /**
     * @inheritDoc
     *
     * @param Entity\Announcement $friendship
     */
    protected function toDocument($friendship)
    {
        $array = $friendship->toArray();

        $array['created'] = new UTCDateTime($friendship->getCreated());
        $array['updated'] = new UTCDateTime($friendship->getUpdated());

        foreach ($array['impulses'] as &$impulse) {

            $impulse['created'] = new UTCDateTime(new \DateTime($impulse['created']));
        }

        return $array;
    }
}
