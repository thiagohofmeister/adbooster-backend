<?php

namespace App\Middleware\Request;

use App\Middleware\MiddlewareAbstract;
use DI\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Detecta scheme/protocol corretamente ao processar requisições por proxy.
 *
 * @author Renato Montagna Junior <renato@moovin.com.br>
 */
class Protocol extends MiddlewareAbstract
{
    /** @var Container */
    private $container;

    /** @var array */
    private $checkSchemeHeaders = [
        'CLOUDFRONT_FORWARDED_PROTO',
        'X_FORWARDED_PROTO'
    ];

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Corrige scheme.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return Response
     */
    public function execute(Request $request, Response $response, $next)
    {
        $scheme = $this->detectScheme($request);

        if ($scheme === null || $scheme === $request->getUri()->getScheme()) {
            return $next($request, $response);
        }

        $uri = $request->getUri()->withScheme($scheme);

        if ($scheme === 'https' && $uri->getPort() === 80) {
            $uri = $uri->withPort(443);
        }

        $request = $request->withUri($uri);
        $this->container->set(Request::class, $request);

        return $next($request, $response);
    }

    /**
     * Detecta scheme.
     *
     * @param Request $request
     *
     * @return null|string
     */
    private function detectScheme(Request $request)
    {
        foreach ($this->checkSchemeHeaders as $header) {
            if ($request->hasHeader($header)) {
                return $request->getHeaderLine($header);
            }
        }
        return null;
    }
}