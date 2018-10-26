<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Abstração dos middlewares.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
abstract class MiddlewareAbstract
{
    /**
     * Aplica alguma regra sobre a requisição ou a resposta.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return mixed
     */
    abstract public function execute(Request $request, Response $response, $next);

    /**
     * Chama o método execute do middleware.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        return $this->execute($request, $response, $next);
    }
}
