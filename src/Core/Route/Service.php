<?php

namespace App\Core\Route;

use Slim\Http\Response;
use Slim\Http\Request;
use App\Controller;
use App\Middleware;

/**
 * Rota para as ações gerais dos módulos.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Service extends AbstractRoute
{
    /**
     * @inheritDoc
     */
    public function __invoke(
        Request $request,
        Response $response,
        string $service,
        string $method = 'index',
        string $parameters = ''
    ): Response {
        $parameters = !empty($parameters) ? explode('/', $parameters) : [];

        $controller = $this->container->get(Controller\Service::class);

        $methodSegments = explode('_', $method);
        foreach ($methodSegments as $k => $v) {
            $methodSegments[$k] = mb_convert_case(mb_convert_case($v, MB_CASE_LOWER), MB_CASE_TITLE);
        }

        $methodName = lcfirst(implode('', $methodSegments));

        return $controller->action($service, $methodName, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return '/{service}/{method}[/{parameters:.*}]';
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewares(): array
    {
        return [
            Middleware\Authentication\User::class,
        ];
    }

    /*
    private function injectService(string $serviceName) {
        $moduleDefinition = $moduleDefinitionRepository->getByTypeAndIdentifier($moduleType, $moduleIdentifier);
        $this->container->set(Entity\Module\Definition::class, $moduleDefinition);
        $this->container->set("{$moduleType}.{$moduleIdentifier}.module_definition", $moduleDefinition);

        try {

            $module = $moduleRepository->getByTypeAndIdentifier($moduleType, $moduleIdentifier);
            $this->container->set(Entity\Module::class, $module);
            $this->container->set("{$moduleType}.{$moduleIdentifier}.module", $module);

        } catch (DataNotFoundException $dataNotFoundException) {
            // Previne fatal error
        }
    }*/
}
