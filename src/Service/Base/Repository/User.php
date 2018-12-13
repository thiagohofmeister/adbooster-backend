<?php

namespace App\Service\Base\Repository;

use App\Exception\Repository\DataNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Database;
use THS\Utils\Date;
use App\Model\Entity;

/**
 * Repositório de operações de banco da coleção de usuários.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class User extends AbstractRepository
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
     * Busca um usuário pelo email.
     *
     * @param string $email
     *
     * @return Entity\User
     *
     * @throws DataNotFoundException
     */
    public function getByEmail(string $email): Entity\User
    {
        $user = $this->findOne(['email' => $email]);

        if (empty($user)) {

            throw new DataNotFoundException(
                [
                    'email' => $email,
                ],
                'Usuário não encontrado pelo email'
            );
        }

        return $user;
    }

    /**
     * Busca um usuário pelo cep de um endereço de entrega.
     *
     * @param string $userCode
     * @param string $zipCode
     *
     * @return Entity\User
     *
     * @throws DataNotFoundException
     */
    public function getByIdAndShippingZipCode(string $userCode, string $zipCode): Entity\User
    {
        $user = $this->findOne(['_id' => new ObjectId($userCode), 'shippingAddresses.zipCode' => $zipCode]);

        if (empty($user)) {

            throw new DataNotFoundException(
                [
                    'shippingAddresses.zipCode' => $zipCode,
                ],
                'Usuário não encontrado'
            );
        }

        return $user;
    }

    /**
     * Retorna uma lista de usuários pela busca.
     *
     * @param string $search
     *
     * @return Entity\User[]
     *
     * @throws \Exception
     */
    public function getBySearch(string $search)
    {
        $query = [
            '$or' => [
                ['email' => ['$regex' => $search, '$options' => 'gi']],
                ['name' => ['$regex' => $search, '$options' => 'gi']],
            ]
        ];

        if ($this->isPaginated()) {

            $this->setPaginationTotal($this->collection->count($query));
        }

        return $this->find($query, [
            'limit' => $this->getLimit(),
            'skip' => $this->getOffset()
        ]);
    }

    /**
     * Busca um usuário pelo id.
     *
     * @param string $userId
     *
     * @return Entity\User
     *
     * @throws DataNotFoundException
     */
    public function getById(string $userId): Entity\User
    {
        $user = $this->findOne(['_id' => new ObjectId($userId)]);

        if (empty($user)) {

            throw new DataNotFoundException(
                [
                    '_id' => $userId,
                ],
                'Usuário não encontrado pelo id'
            );
        }

        return $user;
    }

    /**
     * Busca um usuário autenticado com o token informado e com permissão de acesso ao sistema.
     *
     * @param string $token
     *
     * @return Entity\User
     *
     * @throws DataNotFoundException
     * @throws \Exception
     */
    public function userAuthenticated(string $token): Entity\User
    {
        $user = $this->findOne([
            'authentication.token' => $token,
            'authentication.expires' => ['$gt' => new UTCDateTime((new \DateTime())->sub(\DateInterval::createFromDateString('3 hours')))],
        ]);

        if (empty($user)) {

            throw new DataNotFoundException(
                [
                    'token' => $token,
                ],
                'Usuário não possui permissão no sistema'
            );
        }

        return $user;
    }

    /**
     * Busca um usuário pelo token.
     *
     * @param string $token
     *
     * @return Entity\User
     *
     * @throws DataNotFoundException
     * @throws \Exception
     */
    public function getByToken(string $token): Entity\User
    {
        $user = $this->findOne([
            'authentication.token' => $token,
        ]);

        if (empty($user)) {

            throw new DataNotFoundException(
                [
                    'token' => $token,
                ],
                'Usuário não encontrado'
            );
        }

        return $user;
    }

    /**
     * Prolonga a data de expiração do token do usuário.
     *
     * @param Entity\User $user
     *
     * @return bool
     */
    public function extendsExpiration(Entity\User $user): bool
    {
        $result = $this->collection->updateOne([
            '_id' => $user->getId()
        ], [
            '$set' => [
                'authentication.expires' => new UTCDateTime((new \DateTime())->add(\DateInterval::createFromDateString('20 minutes')))
            ]
        ]);

        return (bool) $result->getModifiedCount();
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

        $document['date'] = $document['date']->toDateTime()
            ->format(Date::JAVASCRIPT_ISO_FORMAT);

        if (!empty($document['authentication'])) {

            $document['authentication']['expires'] = $document['authentication']['expires']->toDateTime()
                ->format(Date::JAVASCRIPT_ISO_FORMAT);
        }

        return Entity\User::fromArray((array) $document);
    }

    /**
     * @inheritDoc
     *
     * @param Entity\User $entity
     */
    protected function toDocument($entity)
    {
        $array = $entity->toArray();

        $array['date'] = new UTCDateTime($entity->getDate());

        if (!empty($array['authentication'])) {
            $array['authentication']['expires'] = new UTCDateTime($entity->getAuthentication()->getExpires());
        }

        return $array;
    }
}
