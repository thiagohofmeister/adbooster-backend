<?php

namespace App\Controller;

use App\Core\Controller;
use App\Service\Base;
use THS\Utils\Enum\HttpStatusCode;
use Slim\Http\Response;

/**
 * Controller de usuários.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class User extends Controller
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
     * @return Response
     */
    public function retrieve(): Response
    {
        $token = $this->request->getHeaderLine('Authorization');

        try {
            $user = $this->userRepository->getByToken($token);

            $userFormatted = $user->toArray();
            $userFormatted['friends'] = count($this->friendshipRepository->getByUserCode($user->getId(), true));

        } catch (\Throwable $throwable) {

            return $this->renderResponse([], HttpStatusCode::NOT_FOUND());
        }

        return $this->renderResponse($userFormatted, HttpStatusCode::OK());
    }
}
