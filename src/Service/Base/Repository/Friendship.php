<?php

namespace App\Service\Base\Repository;

use App\Exception\Repository\DataNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Database;
use THS\Utils\Date;
use App\Model\Entity;

/**
 * Repositório de operações de banco da coleção de amizades.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Friendship extends AbstractRepository
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
     * Retorna as amizades de um usuário.
     *
     * @param string $userCode
     * @param bool $isConfirmed
     *
     * @return Entity\Friendship[]
     *
     * @throws \Exception
     */
    public function getByUserCode(string $userCode, bool $isConfirmed = false)
    {
        $query = [
            '$or' => [
                ['userAdd' => $userCode],
                ['userAdded' => $userCode],
            ]
        ];

        if ($isConfirmed) {

            $query['confirmed'] = $isConfirmed;
        }

        return $this->find($query);
    }

    /**
     * Retorna os pedidos de amizade pendentes de um usuário.
     *
     * @param string $userCode
     *
     * @return Entity\Friendship[]
     *
     * @throws \Exception
     */
    public function getFriendshipsPendingByUser(string $userCode)
    {
        return $this->find([
            'userAdded' => $userCode,
            'confirmed' => false
        ]);
    }

    /**
     * Retorna os pedidos de amizade pendentes de um usuário.
     *
     * @param string $loggedUserCode
     * @param string $inviteUserCode
     *
     * @return Entity\Friendship
     *
     * @throws DataNotFoundException
     */
    public function getInvitePendingByUsers(string $loggedUserCode, string $inviteUserCode): Entity\Friendship
    {
        return $this->findOne([
            'userAdded' => $loggedUserCode,
            'userAdd' => $inviteUserCode,
            'confirmed' => false
        ]);
    }

    /**
     * Retorna uma amizade pelos usuários.
     *
     * @param string $loggedUserCode
     * @param string $inviteUserCode
     *
     * @return Entity\Friendship
     *
     * @throws DataNotFoundException
     */
    public function getInviteByUsers(string $loggedUserCode, string $inviteUserCode): Entity\Friendship
    {
        return $this->findOne([
            'userAdded' => [
                '$in' => [
                    $loggedUserCode,
                    $inviteUserCode,
                ]
            ]
            ,
            'userAdd' => [
                '$in' => [
                    $loggedUserCode,
                    $inviteUserCode,
                ]
            ]
        ]);
    }

    /**
     * @inheritDoc
     *
     * @return Entity\Friendship
     */
    protected function fromDocument($document)
    {
        if (empty($document)) {
            return null;
        }

        if (!empty($document['start'])) {
            $document['start'] = $document['start']->toDateTime()
                ->format(Date::JAVASCRIPT_ISO_FORMAT);
        }

        return Entity\Friendship::fromArray((array) $document);
    }

    /**
     * @inheritDoc
     *
     * @param Entity\Friendship $entity
     */
    protected function toDocument($entity)
    {
        $array = $entity->toArray();

        $array['start'] = new UTCDateTime($entity->getStart());

        return $array;
    }
}
