<?php

namespace App\Service;

use App\Service\Base\Service\Contract;
use App\Model\Entity;
use THS\Utils\Enum\HttpStatusCode;

/**
 * Serviço relacionado aos usuários.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class User extends Contract
{
    public function register()
    {
        $body = $this->getRequest()->getParsedBody();

        $user = Entity\User::fromArray($body);

        return Base\Response::create($user->toArray(), HttpStatusCode::OK());
    }
}
