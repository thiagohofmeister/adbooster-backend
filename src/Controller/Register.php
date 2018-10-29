<?php

namespace App\Controller;

use App\Core\Controller;
use App\Exception\Repository\DataNotFoundException;
use App\Exception\ValidationException;
use App\Service\Base;
use THS\Utils\Converter\Exception\JsonException;
use THS\Utils\Enum\HttpMethod;
use THS\Utils\Enum\HttpStatusCode;
use App\Model\Entity;
use Slim\Http\Response;

/**
 * Controller de registro de usuário.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Register extends Controller
{
    /**
     * @var Base\Repository\User
     * @Inject
     */
    private $userRepository;

    /**
     * @var Base\Validator
     * @Inject
     */
    private $validator;

    /**
     * Cadastra um usuário no sistema.
     *
     * @return Response
     *
     * @throws \Throwable
     */
    public function index(): Response
    {
        if ($this->request->getMethod() != HttpMethod::POST) {
            return $this->renderResponse([], HttpStatusCode::BAD_REQUEST());
        }

        $body = $this->request->getParsedBody();

        if (!$this->validator->validate($body, 'controller/register/save.json')) {

            return $this->renderResponse($this->validator->getErrors(), HttpStatusCode::BAD_REQUEST());
        }

        $user = Entity\User::fromArray($body);

        try {

            if ($this->userRepository->getByEmail($user->getEmail())) {

                return $this->renderResponse(['message' => 'E-mail já cadastrado.'], HttpStatusCode::UNPROCESSABLE_ENTITY());
            }

        } catch (DataNotFoundException $exception) {
            // previne fatal error
        }


        $user->hashPassword();

        $this->userRepository->save($user);

        return $this->renderResponse($this->userRepository->getByEmail($user->getEmail())->toArray(), HttpStatusCode::OK());
    }
}
