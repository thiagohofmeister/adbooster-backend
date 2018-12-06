<?php

namespace App\Service;

use App\Exception\ApiResponseException;
use App\Exception\Repository\DataNotFoundException;
use App\Exception\ValidationException;
use App\Service\Base\Service\Contract;
use THS\Utils\Enum\HttpStatusCode;
use App\Model\Entity;

/**
 * Serviço relacionado as amizades.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
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

            $friendship = $this->friendshipRepository->getInvitePendingByUsers($loggedUserCode, $inviteUserCode);

            $friendship
                ->setConfirmed(true)
                ->setStart(new \DateTime());

            $this->friendshipRepository->save($friendship);

        } catch (DataNotFoundException $dataNotFoundException) {

            throw new \Exception('Pedido de amizade não encontrado', HttpStatusCode::NOT_FOUND);
        }

        return Base\Response::create($friendship->toArray(), HttpStatusCode::OK());
    }

    /**
     * Recusa uma solicitação de amizade.
     *
     * @param string $loggedUserCode
     * @param string $inviteUserCode
     *
     * @return Base\Response
     *
     * @throws \Exception
     */
    public function decline(string $loggedUserCode, string $inviteUserCode): Base\Response
    {
        try {

            $friendship = $this->friendshipRepository->getInvitePendingByUsers($loggedUserCode, $inviteUserCode);

            $this->friendshipRepository->delete($friendship);

        } catch (DataNotFoundException $dataNotFoundException) {

            throw new \Exception('Pedido de amizade não encontrado', HttpStatusCode::NOT_FOUND);
        }

        return Base\Response::create(['message' => 'Pedido de amizade recusado com sucesso.'], HttpStatusCode::OK());
    }

    /**
     * Remove uma amizade.
     *
     * @param string $loggedUserCode
     * @param string $inviteUserCode
     *
     * @return Base\Response
     *
     * @throws \Exception
     */
    public function remove(string $loggedUserCode, string $inviteUserCode): Base\Response
    {
        try {

            $friendship = $this->friendshipRepository->getInviteByUsers($loggedUserCode, $inviteUserCode);

            $this->friendshipRepository->delete($friendship);

        } catch (DataNotFoundException $dataNotFoundException) {

            throw new \Exception('Pedido de amizade não encontrado', HttpStatusCode::NOT_FOUND);
        }

        return Base\Response::create(['message' => 'Amizade removida com sucesso.'], HttpStatusCode::OK());
    }

    /**
     * Adiciona um novo amigo.
     *
     * @return Base\Response
     *
     * @throws \Exception
     */
    public function invite(): Base\Response
    {
        $body = $this->prepareBuildToSave($this->getRequest()->getParsedBody());

        $friendship = Entity\Friendship::fromArray($body);

        try {

            $this->friendshipRepository->getInviteByUsers($friendship->getUserAdded(), $friendship->getUserAdd());

            throw new \Exception("Pedido de amizade já cadastrado.", HttpStatusCode::BAD_REQUEST);

        } catch (DataNotFoundException $dataNotFoundException) {

            // Previne fatal error
        }

        try {

            $this->friendshipRepository->save($friendship);

            return Base\Response::create($friendship->toArray(), HttpStatusCode::OK());

        } catch (\Throwable $throwable) {

            throw new ApiResponseException($throwable->getMessage(), HttpStatusCode::BAD_REQUEST());
        }
    }

    /**
     * Prepara os dados do body para poder ser construído.
     * Completa os dados do body com informações para poder criar um pedido de amizade.
     *
     * @param $body
     *
     * @return array
     */
    private function prepareBuildToSave($body)
    {
        $body['confirmed'] = false;

        return $body;
    }
}
