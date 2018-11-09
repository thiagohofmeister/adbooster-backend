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
     * Busca uma amizade pelo código do usuário.
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
     * @param Entity\Friendship $friendship
     */
    protected function toDocument($friendship)
    {
        $array = $friendship->toArray();

        $array['start'] = new UTCDateTime($friendship->getStart());

        return $array;
    }
}
