<?php

namespace App\Middleware\Response;

use App\Middleware\MiddlewareAbstract;
use THS\Utils\Enum\HttpStatusCode;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Middleware responsável por retornar sucesso ao receber qualquer requisição com método OPTIONS.
 *
 * @package App\Middleware\Response
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Options extends MiddlewareAbstract
{
    /**
     * Retorna resposta com status 200 caso método requisição seja OPTIONS.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return Response
     */
    public function execute(Request $request, Response $response, $next)
    {
        if ($request->getMethod() == 'OPTIONS') {
            return $response
                ->withJson([], HttpStatusCode::OK);
        }

        return $next($request, $response);
    }
}
