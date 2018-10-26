<?php

namespace App\Core\Route;

use DI\Container;

/**
 * Abstração das rotas do sistema.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
abstract class AbstractRoute
{
    /** @var Container */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Retorna o padrão no qual esta rota será disparada.
     *
     * @return string
     */
    abstract public function getPattern(): string;

    /**
     * Retorna os middlewares da rota.
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return [];
    }
}
