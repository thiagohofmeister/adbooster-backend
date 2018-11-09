<?php

namespace App\Service;

use App\Exception\Repository\DataNotFoundException;
use App\Service\Base\Service\Contract;
use THS\Utils\Enum\HttpStatusCode;

/**
 * Serviço relacionado as amizades.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Friendship extends Contract
{
    /**
     * @var Base\Repository\Friendship
     * @Inject
     */
    private $friendshipRepository;

    public function index()
    {

    }

    /**
     * Aceita uma solicitação de amizade.
     *
     * @param string $loggedUserCode
     * @param string $inviteUserCode
     *
     * @return Base\Response
     *
     * @throws \Exception
     */
    public function accept(string $loggedUserCode, string $inviteUserCode): Base\Response
    {
        try {

            $friendship = $this->friendshipRepository->getFriendshipByUsers($loggedUserCode, $inviteUserCode);

            $friendship
                ->setConfirmed(true)
                ->setStart(new \DateTime());

            $this->friendshipRepository->save($friendship);

        } catch (DataNotFoundException $dataNotFoundException) {

            throw new \Exception('Pedido de amizade não encontrado', HttpStatusCode::NOT_FOUND);
        }

        return Base\Response::create($friendship->toArray(), HttpStatusCode::OK());
    }
}
