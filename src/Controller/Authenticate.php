<?php

namespace App\Controller;

use App\Core\Controller;
use App\Service\Base\Repository;
use THS\Utils\Enum\HttpStatusCode;
use App\Model\Element;
use App\Model\Entity;
use Slim\Http\Response;
use THS\Utils\Hash;

/**
 * Controller de autenticação.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Authenticate extends Controller
{
    /** @var string Texto usado para gerar os tokens de autenticação. */
    const GENERATE_TOKEN_SEED = '65-6([Q#TDWtb>dxzFy$n&;NuK-*(q1pZt6"6[aO1-DP:yb(gZ:0zrX3PO@pdenb';

    /**
     * @var Repository\User
     * @Inject
     */
    private $userRepository;

    /**
     * Loga um usuário no sistema a partir do email e senha.
     *
     * @return Response
     */
    public function index(): Response
    {
        $body = $this->request->getParsedBody();

        try {

            $user = $this->userRepository->getByEmail($body['email']);

            $this->checkAuthentication($user, $body['password']);

            $authentication = (new Element\User\Authentication)
                ->setExpires((new \DateTime())->add(\DateInterval::createFromDateString('20 minutes')))
                ->setToken($this->generateToken($user));

            $user->setAuthentication($authentication);

            $this->userRepository->save($user);

            return $this->renderResponse($this->userRepository->getByEmail($user->getEmail())->toArray(), HttpStatusCode::OK());

        } catch (\Throwable $throwable) {

            return $this->renderResponse(
                ['message' => $throwable->getMessage()],
                HttpStatusCode::UNAUTHORIZED()
            );

        }
    }

    /**
     * Gera o token para um usuário do sistema.
     *
     * @param Entity\User $user
     *
     * @return string
     */
    private function generateToken(Entity\User $user): string
    {
        $token = $user->getId() . $user->getEmail() . time() . self::GENERATE_TOKEN_SEED;

        return base64_encode(md5($token));
    }

    /**
     * Verifica se a senha do usuário confere.
     *
     * @param Entity\User $user
     * @param string $password
     *
     * @return bool
     *
     * @throws \Exception
     */
    private function checkAuthentication(Entity\User $user, string $password): bool
    {
        if (!Hash::check($password, $user->getPassword())) {
            throw new \Exception("E-mail e/ou senha inválidos.");
        }

        return true;
    }
}
