<?php

namespace App\Service\Base;

use App\Exception\ValidationException;
use Eloquent\Enumeration\Exception\UndefinedMemberExceptionInterface;
use THS\Utils\Enum\HttpMethod;
use THS\Utils\Enum\HttpStatusCode;
use Slim\Http\Request;

/**
 * Chama métodos dos serviços.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Caller
{
    /** @var Loader\Resource */
    private $resourceLoader;

    /** @var Request */
    private $request;

    /** @var Validator */
    private $validator;

    /**
     * @param Loader\Resource $resourceLoader
     * @param Request $request
     * @param Validator $validator
     */
    public function __construct(Loader\Resource $resourceLoader, Request $request, Validator $validator)
    {
        $this->resourceLoader = $resourceLoader;
        $this->request = $request;
        $this->validator = $validator;
    }

    /**
     * Chama um serviço.
     *
     * @param string $actionName
     * @param string $actionMethod
     * @param array $parameters
     *
     * @throws ValidationException
     *
     * @return Response
     */
    public function action(string $actionName, string $actionMethod, array $parameters = []): Response
    {
        try {

            $action = $this->resourceLoader->service($actionName);

            $methodSegments = explode('_', $actionMethod);
            foreach ($methodSegments as $k => $v) {
                $methodSegments[$k] = mb_convert_case(mb_convert_case($v, MB_CASE_LOWER), MB_CASE_TITLE);
            }

            $methodName = lcfirst(implode('', $methodSegments));

            if (!method_exists($action, $methodName)) {

                return Response::create([
                    'message' => 'Método da action não foi encontrado'
                ], HttpStatusCode::NOT_FOUND());
            }

            if ($this->request->getMethod() == HttpMethod::POST) {

                if (empty($this->request->getParsedBody()) && empty($this->request->getUploadedFiles())) {

                    throw new \Exception('O corpo da requisição é obrigatório');
                }

                try {

                    $schema = "service/{$actionName}/{$actionMethod}.json";
                    if (!$this->validator->validate($this->request->getParsedBody(), $schema)) {

                        return Response::create($this->validator->getErrors(), HttpStatusCode::BAD_REQUEST());
                    }
                } catch (\Throwable $throwable) {
                    // Previne fatal error
                }
            }

            return call_user_func_array([$action, $methodName], $parameters);

        } catch (ValidationException $exception) {

            throw $exception;

        } catch (\Throwable $throwable) {

            try {
                $statusCode = HttpStatusCode::memberByValue($throwable->getCode());

            } catch (UndefinedMemberExceptionInterface $e) {

                $statusCode = HttpStatusCode::BAD_REQUEST();
            }

            return Response::create([
                'message' => $throwable->getMessage()
            ], $statusCode);
        }
    }
}
