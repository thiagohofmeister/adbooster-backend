<?php

namespace App\Core\Route;

use App\Controller;
use App\Exception\ApiResponseException;
use App\Middleware;
use THS\Utils\Enum\HttpStatusCode;
use Slim\Http\Response;
use Slim\Http\Request;

/**
 * Rota para as ações de usuário.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class User extends AbstractRoute
{
    /**
     * @inheritDoc
     */
    public function __invoke(Request $request, Response $response, string $method = 'index', string $parameters = ''): Response
    {
        $parameters = !empty($parameters) ? explode('/', $parameters) : [];

        $controller = $this->container->get(Controller\User::class);

        $methodSegments = explode('_', $method);
        foreach ($methodSegments as $k => $v) {
            $methodSegments[$k] = mb_convert_case(mb_convert_case($v, MB_CASE_LOWER), MB_CASE_TITLE);
        }

        $methodName = lcfirst(implode('', $methodSegments));

        if (!method_exists($controller, $methodName)) {

            throw new ApiResponseException('Método da controler não foi encontrado ' . $controller, HttpStatusCode::BAD_REQUEST());
        }

        return call_user_func_array([$controller, $methodName], $parameters);
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return '/user[/[{method}[/[{parameters.*}]]]]';
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewares(): array
    {
        return [
            Middleware\Authentication\User::class
        ];
    }
}
