<?php

namespace App\Core;

use App\Core\Enum\Environment;
use App\Exception\ApiResponseException;
use App\Exception\Repository\DataNotFoundException;
use THS\Utils\Enum\HttpStatusCode;
use THS\Utils\Exception\APIException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Trata os erros da aplicação.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class ErrorHandler
{
    /**
     * @var Environment
     * @Inject
     */
    private $environment;

    /**
     * Trata os erros de exceção da aplicação.
     *
     * @param Request $request
     * @param Response $response
     * @param \Throwable $exception
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, \Throwable $exception): Response
    {
        if ($exception instanceof DataNotFoundException) {

            return $response
                ->withJson([
                    'message' => $exception->getMessage(),
                    'data' => $exception->getData(),
                ], HttpStatusCode::NOT_FOUND);
        }

        if ($exception instanceof APIException) {

            return $response
                ->withJson([
                    'message' => $exception->getMessage()
                ], HttpStatusCode::BAD_REQUEST);
        }

        if ($exception instanceof ApiResponseException) {

            $response
                ->withJson([
                    'message' => $exception->getMessage()
                ], $exception->getCode());

            if (!empty($exception->getHeaders())) {

                foreach ($exception->getHeaders() as $name => $value) {

                    if ($this->environment->value() == Environment::DEVELOPMENT && in_array($name, ['Cache-Control'])) {
                        continue;
                    }

                    $response = $response->withHeader($name, $value);
                }
            }

            return $response;
        }

        return $response
            ->withJson([
                'message' => $exception->getMessage()
            ], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}
