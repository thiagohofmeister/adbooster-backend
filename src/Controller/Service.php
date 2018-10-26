<?php

namespace App\Controller;

use App\Core\Controller;
use App\Exception\ValidationException;
use THS\Utils\Enum\HttpStatusCode;
use Slim\Http\Response;
use App\Service\Base;

/**
 * Controller de serviços.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Service extends Controller
{
    /**
     * @var Base\Caller
     * @Inject
     */
    private $serviceCaller;

    /**
     * Redireciona para as ações dos módulos.
     *
     * @param string $actionName
     * @param string $actionMethod
     * @param array $parameters
     *
     * @return Response
     */
    public function action(string $actionName, string $actionMethod = 'index', array $parameters = []): Response
    {
        try {

            $response = $this->serviceCaller->action($actionName, $actionMethod, $parameters);

            return $this->renderResponse($response->getData(), $response->getStatusCode(), $response->getHeaders());

        } catch (ValidationException $exception) {

            return $this->renderValidationExceptionResponse($exception);

        } catch (\Throwable $throwable) {

            return $this->renderResponse(['message' => $throwable->getMessage()], HttpStatusCode::BAD_REQUEST());
        }
    }
}
