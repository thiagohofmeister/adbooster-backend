<?php

namespace App\Core;

use App\Core\Route;
use DI\Bridge\Slim;
use App\Core\Enum\Environment;
use App\Middleware;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Http\Request;

/**
 * Aplicação.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class App extends Slim\App
{
    /** @var string Caminho para o arquivo de configurações. */
    private $configPath;

    /** @var Environment Ambiente no qual a aplicação está sendo executada. */
    private $environment;

    /**
     * @param string $configPath
     * @param Environment $environment
     */
    public function __construct(string $configPath, Environment $environment)
    {
        $this->configPath = $configPath;
        $this->environment = $environment;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->useAnnotations(true)
            ->addDefinitions($this->configPath);
    }

    /**
     * @inheritDoc
     */
    public function run($silent = false)
    {
        $container = $this->getContainer()->get(Container::class);
        $container->set(Request::class, $this->getContainer()->get('request'));

        $container->set(Environment::class, $this->environment);
        $container->set('errorHandler', $container->get(ErrorHandler::class));
        $container->set('phpErrorHandler', $container->get(ErrorHandler::class));

        $this->registerRoutes();
        $this->registerDefaultMiddleware();

        return parent::run($silent);
    }

    /**
     * Registra os middlewares padrão da aplicação.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function registerDefaultMiddleware()
    {
        $middlewares = [
            Middleware\Request\Protocol::class,
            Middleware\Response\Header::class,
            Middleware\Response\Options::class,
        ];

        foreach (array_reverse($middlewares) as $middleware) {

            $this->add($this->getContainer()->get($middleware));
        }
    }

    /**
     * Registra as rotas da aplicação.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function registerRoutes()
    {
        $routes = [
            Route\Status::class,
            Route\User::class,
            Route\Authenticate::class,
            Route\Register::class,
            Route\Service::class,
        ];

        foreach ($routes as $routeClass) {

            $route = $this->getContainer()->get($routeClass);
            $slimRoute = $this->any($route->getPattern(), get_class($route));

            foreach (array_reverse($route->getMiddlewares()) as $middleware) {
                $slimRoute->add($middleware);
            }
        }
    }
}
