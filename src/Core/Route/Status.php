<?php

namespace App\Core\Route;

use Slim\Http\Response;

/**
 * Rota para validar o acesso aw aplicação.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Status extends AbstractRoute
{
    /**
     * @inheritDoc
     */
    public function __invoke(Response $response): Response
    {
        return $response;
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return '/status[/]';
    }
}
