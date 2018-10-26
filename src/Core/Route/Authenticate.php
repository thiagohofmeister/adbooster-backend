<?php

namespace App\Core\Route;

use App\Controller;
use Slim\Http\Response;

/**
 * Rota para autenticação de usuários.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Authenticate extends AbstractRoute
{
    /**
     * @inheritDoc
     */
    public function __invoke(Response $response): Response
    {
        $controller = $this->container->get(Controller\Authenticate::class);
        return $controller->index();
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return '/authenticate[/]';
    }
}
