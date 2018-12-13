<?php

namespace App\Service;

use App\Exception\ApiResponseException;
use App\Exception\Repository\DataNotFoundException;
use App\Exception\ValidationException;
use App\Model\Element;
use App\Service\Base\Service\Contract;
use App\Service\Base;
use MongoDB\BSON\UTCDateTime;
use THS\Utils\Date;
use THS\Utils\Enum\HttpStatusCode;
use App\Model\Entity;
use App\Enum;

/**
 * Serviço relacionado aos anúncios.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Announcement extends Contract
{
    /**
     * @var Base\Repository\Announcement
     * @Inject
     */
    private $announcementRepository;

    /**
     * @var Base\Repository\Friendship
     * @Inject
     */
    private $friendshipRepository;

    /**
     * @var Base\Repository\User
     * @Inject
     */
    private $userRepository;

    /**
     * Retorna os anúncios.
     *
     * @return Base\Response
     *
     * @throws DataNotFoundException
     */
    public function index(): Base\Response
    {
        $total = 0;

        $page = $this->getRequest()->getQueryParam('page') ?: 1;
        $limit = $this->getRequest()->getQueryParam('limit') ?: 0;

        $userCode = $this->getRequest()->getQueryParam('userCode');

        try {

            $friendships = $this->friendshipRepository->getByUserCode($userCode);

            $friends = [];
            foreach ($friendships as $friendship) {

                $friend = $friendship->getUserAdded();
                if ($userCode === $friend) {

                    $friend = $friendship->getUserAdd();
                }

                $friends[] = $friend;
            }

            $announcements = $this->announcementRepository
                ->setPaginated($page, $limit)
                ->getByUserAndFriends($userCode, $friends);

            $total = $this->announcementRepository->getPaginationTotal();


        } catch (\Throwable $throwable) {

            $announcements = [];
        }

        $formattedAnnouncements = [];
        foreach ($announcements as $announcement) {

            if ($announcement->getStatus()->value() !== Enum\Announcement\Status::ACTIVE) {
                continue;
            }

            $impulse = reset($announcement->getImpulses());

            $this->announcementRepository->fillImpulses($announcement);

            $announcementFormatted = $announcement->toArray();

            $announcementFormatted['sharedBy'] = $this->userRepository->getById($impulse->getOwner())->toArray();
            $announcementFormatted['impulseDate'] = $impulse->getCreated()->format(Date::JAVASCRIPT_ISO_FORMAT);

            $formattedAnnouncements[] = $announcementFormatted;
        }

        return Base\Response::create([
            'total' => $total,
            'items' => $formattedAnnouncements
        ], HttpStatusCode::OK());
    }

    /**
     * Retorna um anúncio.
     *
     * @param string $code
     * @param string $sharedCode
     *
     * @return Base\Response
     */
    public function retrieve(string $code, string $sharedCode): Base\Response
    {
        try {

            $announcement = $this->announcementRepository
                ->getById($code);

            $this->announcementRepository->fillImpulses($announcement);

            $announcementFormatted = $announcement->toArray();

            $announcementFormatted['sharedBy'] = $this->userRepository->getById($sharedCode)->toArray();

            foreach ($announcement->getImpulses() as $impulse) {

                if ($impulse->getOwner() === $sharedCode) {

                    $announcementFormatted['impulseDate'] = $impulse->getCreated()->format(Date::JAVASCRIPT_ISO_FORMAT);
                    break;
                }
            }

            return Base\Response::create($announcementFormatted, HttpStatusCode::OK());

        } catch (\Throwable $throwable) {

            return Base\Response::create(['Anúncio não encontrado.'], HttpStatusCode::NOT_FOUND());
        }
    }

    /**
     * Publica um anúncio.
     *
     * @return Base\Response
     *
     * @throws ApiResponseException
     * @throws ValidationException
     */
    public function publish()
    {
        try {

            $body = $this->prepareBuildToSave($this->getRequest()->getParsedBody());

            $announcement = Entity\Announcement::fromArray($body);

            if ($announcement->getCurrentPrice() <= 0) {
                throw new ValidationException(
                    'currentPrice',
                    ValidationException::GREATER_THAN,
                    $announcement->getCurrentPrice(),
                    'Preço atual deve ser mais que ZERO.'
                );
            }

            $this->announcementRepository->save($announcement);

            return Base\Response::create($announcement->toArray(), HttpStatusCode::OK());

        } catch (ValidationException $exception) {

            throw $exception;

        } catch (\Throwable $throwable) {

            throw new ApiResponseException($throwable->getMessage(), HttpStatusCode::BAD_REQUEST());
        }
    }

    /**
     * Cadastra/Remove um impulso de um anúncio.
     *
     * @return Base\Response
     *
     * @throws ApiResponseException
     * @throws ValidationException
     */
    public function addImpulse()
    {
        try {

            $body = $this->prepareBuildToSave($this->getRequest()->getParsedBody());

            $impulse = Element\Impulse::fromArray($body['impulse'])->toArray();

            $impulse['created'] = new UTCDateTime(new \DateTime($impulse['created']));

            $this->announcementRepository->push($body['announcementId'], [
                'impulses' => $impulse
            ]);

            $announcement = $this->announcementRepository->getById($body['announcementId']);

            return Base\Response::create($announcement->toArray(), HttpStatusCode::OK());

        } catch (ValidationException $exception) {

            throw $exception;

        } catch (\Throwable $throwable) {

            throw new ApiResponseException($throwable->getMessage(), HttpStatusCode::BAD_REQUEST());
        }
    }

    /**
     * Cadastra/Remove um impulso de um anúncio.
     *
     * @return Base\Response
     *
     * @throws ApiResponseException
     * @throws ValidationException
     */
    public function removeImpulse()
    {
        try {

            $body = $this->prepareBuildToSave($this->getRequest()->getParsedBody());

            $impulse = Element\Impulse::fromArray($body['impulse'])->toArray();

            unset($impulse['created']);

            $this->announcementRepository->pull($body['announcementId'], [
                'impulses' => $impulse
            ]);

            $announcement = $this->announcementRepository->getById($body['announcementId']);

            return Base\Response::create($announcement->toArray(), HttpStatusCode::OK());

        } catch (ValidationException $exception) {

            throw $exception;

        } catch (\Throwable $throwable) {

            throw new ApiResponseException($throwable->getMessage(), HttpStatusCode::BAD_REQUEST());
        }
    }

    /**
     * Prepara os dados do body para poder ser construído.
     * Completa os dados do body com informações para poder criar um anúncio.
     *
     * @param $body
     *
     * @return array
     */
    private function prepareBuildToSave($body)
    {
        $body['status'] = Enum\Announcement\Status::ACTIVE;
        $body['impulses'][] = [
            'owner' => $body['creator']['code'],
            'origin' => null
        ];

        return $body;
    }
}
