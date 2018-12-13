<?php

namespace App\Service;

use App\Exception\ApiResponseException;
use App\Exception\Repository\DataNotFoundException;
use App\Exception\ValidationException;
use App\Model\Element;
use App\Service\Base\Service\Contract;
use App\Service\Base;
use http\Exception;
use THS\Utils\Enum\HttpStatusCode;

/**
 * Serviço relacionado aos usuários.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class User extends Contract
{
    /**
     * @var Base\Repository\User
     * @Inject
     */
    private $userRepository;

    /**
     * @var Base\Repository\Friendship
     * @Inject
     */
    private $friendshipRepository;

    /**
     * @var \App\Model\Entity\User
     * @Inject
     */
    private $userLogged;

    /**
     * Retorna lista de usuários.
     *
     * @param string $search
     *
     * @return Base\Response
     *
     * @throws \Exception
     */
    public function search(string $search): Base\Response
    {
        $total = 0;

        $page = $this->getRequest()->getQueryParam('page') ?: 1;
        $limit = $this->getRequest()->getQueryParam('limit') ?: 0;

        try {

            $users = $this->userRepository
                ->setPaginated($page, $limit)
                ->getBySearch($search);

            $total = $this->userRepository->getPaginationTotal();

        } catch (\Throwable $throwable) {

            $users = [];
        }

        if (empty($users)) {
            throw new \Exception('Nenhum usuário encontrado.', HttpStatusCode::NOT_FOUND);
        }

        $formattedUsers = [];
        foreach ($users as $user) {

            if ((string) $this->userLogged->getId() == (string) $user->getId()) {
                continue;
            }

            $formattedUser = $user->toArray();

            try {

                $friendship = $this->friendshipRepository->getInviteByUsers($this->userLogged->getId(), $user->getId());

                $formattedUser['statusAdd'] = 'pending';
                if ($friendship->isConfirmed()) {

                    $formattedUser['statusAdd'] = 'confirmed';
                }

            } catch (DataNotFoundException $dataNotFoundException) {
                // previne fatal error
            }

            $formattedUsers[] = $formattedUser;
        }

        return Base\Response::create([
            'total' => $total,
            'items' => $formattedUsers
        ], HttpStatusCode::OK());
    }

    /**
     * Busca um usuário pelo token.
     *
     * @return Base\Response
     */
    public function retrieve(): Base\Response
    {
        $token = $this->getRequest()->getHeaderLine('Authorization');

        try {
            $user = $this->userRepository->getByToken($token);

            $userFormatted = $user->toArray();
            $userFormatted['friends'] = (int) count($this->friendshipRepository->getByUserCode($user->getId(), true));

        } catch (\Throwable $throwable) {

            return Base\Response::create([], HttpStatusCode::NOT_FOUND());
        }

        return Base\Response::create($userFormatted, HttpStatusCode::OK());
    }

    /**
     * Adiciona um novo endereço de entrega.
     *
     * @return Base\Response
     *
     * @throws ApiResponseException
     * @throws ValidationException
     */
    public function addShippingAddress(): Base\Response
    {
        try {
            $body = $this->getRequest()->getParsedBody();

            $shippingAddress = Element\User\Address::fromArray($body);

            $user = $this->userRepository->getByIdAndShippingZipCode((string) $this->userLogged->getId(), $shippingAddress->getZipCode());

            if (!empty($user)) {
                throw new ApiResponseException('Endereço já cadastrado.', HttpStatusCode::UNPROCESSABLE_ENTITY());
            }

        } catch (DataNotFoundException $dataNotFoundException) {
            // previne fatal error
        }

        try {

            $shippingAddresses = $this->userLogged->getShippingAddresses();
            $shippingAddresses[] = $shippingAddress;

            $this->userRepository->save($this->userLogged->setShippingAddresses($shippingAddresses));

            return Base\Response::create($this->userLogged->toArray(), HttpStatusCode::OK());

        } catch (ValidationException $exception) {

            throw $exception;

        } catch (\Throwable $throwable) {

            throw new ApiResponseException($throwable->getMessage(), HttpStatusCode::BAD_REQUEST());
        }
    }

    public function index()
    {

    }

    /**
     * Retorna solicitações de amizade pendentes.
     *
     * @param string $userCode
     *
     * @return Base\Response
     *
     * @throws \Exception
     */
    public function retrieveFriendshipsPending(string $userCode): Base\Response
    {
        $friendships = $this->friendshipRepository->getFriendshipsPendingByUser($userCode);

        if (empty($friendships)) {
            throw new \Exception('Não há solicitações de amizade pendentes.', HttpStatusCode::NOT_FOUND);
        }

        $users = [];
        foreach ($friendships as $friendship) {
            $users[] = $this->userRepository->getById($friendship->getUserAdd())->toArray();
        }

        return Base\Response::create($users, HttpStatusCode::OK());
    }
}
