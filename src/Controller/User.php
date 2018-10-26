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
     * Busca um usuário pelo token.
     *
     * @return Response
     */
    public function retrieve(): Response
    {
        $token = $this->request->getHeaderLine('Authorization');

        try {
            $user = $this->userRepository->getByToken($token);

        } catch (\Throwable $exception) {

            return $this->renderResponse([], HttpStatusCode::NOT_FOUND());
        }

        return $this->renderResponse($user->toArray(), HttpStatusCode::OK());
    }
}
