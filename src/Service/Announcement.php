<?php

namespace App\Service;

use App\Exception\ApiResponseException;
use App\Exception\Repository\DataNotFoundException;
use App\Exception\ValidationException;
use App\Service\Base\Service\Contract;
use App\Service\Base;
use THS\Utils\Enum\HttpStatusCode;
use App\Model\Entity;
use App\Enum;

/**
 * Serviço relacionado aos anúncios.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
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
            foreach ($friendships as $friend) {
                $friends[] = $friend->getUserAdded();
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

            $sharedBy = reset($announcement->getImpulses())->toArray()['owner'];

            $this->announcementRepository->fillImpulses($announcement);

            $announcementFormatted = $announcement->toArray();

            $announcementFormatted['sharedBy'] = $sharedBy;

            $formattedAnnouncements[] = $announcementFormatted;
        }

        return Base\Response::create([
            'total' => $total,
            'items' => $formattedAnnouncements
        ], HttpStatusCode::OK());
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
            'owner' => $body['creator'],
            'origin' => null
        ];

        return $body;
    }
}
