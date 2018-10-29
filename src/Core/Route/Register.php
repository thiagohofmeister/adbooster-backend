<?php

namespace App\Core\Route;

use App\Controller;
use Slim\Http\Response;

/**
 * Rota para registro de usuários.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Register extends AbstractRoute
{
    /**
     * @inheritDoc
     */
    public function __invoke(Response $response): Response
    {
        $controller = $this->container->get(Controller\Register::class);
        return $controller->index();
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return '/register[/]';
    }
}
