<?php

namespace App\Middleware\Authentication;

use App\Middleware\MiddlewareAbstract;
use App\Service\Base;
use DI\Container;
use THS\Utils\Enum\HttpStatusCode;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\Entity;

/**
 * Autenticação de requisições feitas por um usuário específico da aplicação.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class User extends MiddlewareAbstract
{
    /** @var Base\Repository\User */
    private $userRepository;

    /** @var Container */
    private $container;

    /**
     * @param Base\Repository\User $userRepository
     * @param Container $container
     */
    public function __construct(Base\Repository\User $userRepository, Container $container)
    {
        $this->userRepository = $userRepository;
        $this->container = $container;
    }

    /**
     * Authenticação das requisições feitas para o sistema.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function execute(Request $request, Response $response, $next)
    {
        $token = $request->getHeaderLine('Authorization');

        try {

            $user = $this->getUserAuthenticated($token);

            $this->userRepository->extendsExpiration($user);

            $this->container->set(Entity\User::class, $user);

        } catch (\Throwable $throwable) {

            return $response
                ->withStatus(HttpStatusCode::UNAUTHORIZED)
                ->withHeader('WWW-Authorization', 'app')
                ->withHeader('Access-Control-Expose-Headers', 'WWW-Authorization');

        }

        return $next($request, $response);
    }

    /**
     * Busca o usuário do token se estiver permissão de acesso à loja informada.
     *
     * @param string $token
     *
     * @return Entity\User
     *
     * @throws \App\Exception\Repository\DataNotFoundException
     * @throws \Exception
     */
    private function getUserAuthenticated(string $token): Entity\User
    {
        return $this->userRepository->userAuthenticated($token);
    }
}
