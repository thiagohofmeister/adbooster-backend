<?php

namespace App\Service\Base\Loader;

use App\Model\Entity;
use DI;
use App\Service\Base;
use App\Service\Tool\Batch;

/**
 * Carrega recursos dos módulos.
 * Injeta perfil de configurações, facade do pacote e facade do módulo.
 * Constrói objetos de ação, fila e cron.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Resource
{
    /** @var DI\Container */
    private $container;

    /** @var Path */
    private $pathLoader;

    /**
     * @param DI\Container $container
     * @param Path $pathLoader
     */
    public function __construct(DI\Container $container, Path $pathLoader)
    {
        $this->container = $container;
        $this->pathLoader = $pathLoader;
    }

    /**
     * Carrega o objeto do serviço.
     *
     * @param string $actionName
     *
     * @return Base\Service\Contract
     *
     * @throws \Exception Caso a ação não seja encontrada ou a ação não implemente a interface.
     */
    public function service(string $actionName): Base\Service\Contract
    {
        $actionPath = $this->pathLoader->service($actionName);

        if (!class_exists($actionPath)) {
            throw new \Exception('Serviço não encontrado.');
        }

        $action = $this->container->get($actionPath);

        if (!($action instanceof Base\Service\Contract)) {
            throw new \Exception('Serviço não implementa interface.');
        }

        return $action;
    }
}
