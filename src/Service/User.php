<?php

namespace App\Service;

use App\Service\Base\Service\Contract;
use App\Service\Base;
use THS\Utils\Enum\HttpStatusCode;

/**
 * Serviço relacionado aos usuários.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
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
