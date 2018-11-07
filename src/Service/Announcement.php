<?php

namespace App\Service;

use App\Exception\Repository\DataNotFoundException;
use App\Service\Base\Service\Contract;
use App\Service\Base;
use THS\Utils\Enum\HttpStatusCode;

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

            $user = $this->userRepository->getById($userCode);

            $friends = [];
            foreach ($user->getFriends() as $friend) {
                $friends[] = $friend->getCode();
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
}