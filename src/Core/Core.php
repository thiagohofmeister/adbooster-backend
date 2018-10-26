<?php

namespace App\Core;

use App\Core\Enum\Environment;
use App\Exception\ValidationException;
use THS\Utils;
use Slim\Http\Response;
use Slim\Http\Request;

/**
 * Abstração de todas classes do projeto.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
abstract class Core
{
    /**
     * @var Request Requisição recebida.
     * @Inject
     */
    protected $request;

    /**
     * @var Response Resposta a ser retornada.
     * @Inject
     */
    protected $response;

    /**
     * @var Environment Ambiente no qual a aplicação está sendo executada.
     * @Inject
     */
    protected $environment;

    /**
     * Renderiza a saída no formato json.
     *
     * @param array|null $data
     * @param Utils\Enum\HttpStatusCode $httpStatusCode
     * @param array $headers
     *
     * @return Response
     */
    protected function renderResponse($data, Utils\Enum\HttpStatusCode $httpStatusCode, array $headers = []): Response
    {
        if (empty($data)) {
            $data = [];
        }

        $response = $this->response->withJson($data, $httpStatusCode->value());

        if (!empty($headers)) {

            foreach ($headers as $name => $value) {

                if ($this->environment->value() == Environment::DEVELOPMENT && in_array($name, ['Cache-Control'])) {
                    continue;
                }

                $response = $response->withHeader($name, $value);
            }
        }

        return $response;
    }

    /**
     * Renderiza a saída no formato json com dados da exceção de validação.
     *
     * @param ValidationException $exception
     *
     * @return Response
     */
    protected function renderValidationExceptionResponse(ValidationException $exception): Response
    {
        $data = [
            'property' => $exception->getProperty(),
            'message' => $exception->getMessage(),
            'constraint' => $exception->getConstraint(),
        ];

        if (!is_null($exception->getConstraintValue())) {
            $data[$exception->getConstraint()] = $exception->getConstraintValue();
        }

        return $this->renderResponse([
            'message' => 'Validation failed',
            'data' => [
                $data
            ]
        ], Utils\Enum\HttpStatusCode::BAD_REQUEST());
    }
}
