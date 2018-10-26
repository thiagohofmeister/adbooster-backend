<?php

namespace App\Middleware\Response;

use App\Middleware\MiddlewareAbstract;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Middleware responsável por adicionar Headers ao response.
 *
 * @package App\Middleware\Response
 *
 * @author Cassiano de Mesquita <cassiano.mesquita@moovin.com.br>
 */
class Header extends MiddlewareAbstract
{
    /**
     * Adiciona headers de acesso.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return Response
     */
    public function execute(Request $request, Response $response, $next)
    {
        $response = $next($request, $response);

        return $response
            ->withAddedHeader('Access-Control-Allow-Origin', '*')
            ->withAddedHeader('Access-Control-Allow-Headers', 'Authorization,Content-Type,X-Merchant,Cache-Control,MercadoLivre-Account');
    }
}
