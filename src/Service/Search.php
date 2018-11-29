<?php

namespace App\Service;

use App\Exception\Repository\DataNotFoundException;
use App\Service\Base\Service\Contract;
use THS\Utils\Enum\HttpStatusCode;

/**
 * @todo Document class Search.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Search extends Contract
{
    /**
     * @var Base\Repository\User
     * @Inject
     */
    private $userRepository;

    /**
     * @var Base\Repository\Friendship
     * @Inject
     */
    private $friendshipRepository;

    /**
     * @var \App\Model\Entity\User
     * @Inject
     */
    private $userLogged;

    /**
     * @var Base\Repository\Announcement
     * @Inject
     */
    private $announcementRepository;

    /**
     * Retorna lista de usuários e anúncios.
     *
     * @param string $search
     *
     * @return Base\Response
     *
     * @throws \Exception
     */
    public function retrieve(string $search): Base\Response
    {
        $total = 0;

        $page = $this->getRequest()->getQueryParam('page') ?: 1;
        $limit = $this->getRequest()->getQueryParam('limit') ?: 0;

        try {

            $users = $this->userRepository
                ->setPaginated($page, $limit)
                ->getBySearch($search);

            $total = $this->userRepository->getPaginationTotal();

        } catch (\Throwable $throwable) {

            $users = [];
        }

        if (empty($users)) {
            throw new \Exception('Nenhum usuário encontrado.', HttpStatusCode::NOT_FOUND);
        }

        $formattedUsers = [];
        foreach ($users as $user) {

            if ((string) $this->userLogged->getId() == (string) $user->getId()) {
                continue;
            }

            $formattedUser = $user->toArray();

            try {

                $friendship = $this->friendshipRepository->getInviteByUsers($this->userLogged->getId(), $user->getId());

                $formattedUser['statusAdd'] = 'pending';
                if ($friendship->isConfirmed()) {

                    $formattedUser['statusAdd'] = 'confirmed';
                }

            } catch (DataNotFoundException $dataNotFoundException) {
                // previne fatal error
            }

            $formattedUsers[] = $formattedUser;
        }

        $userCode = (string) $this->userLogged->getId();

        try {

            $friendships = $this->friendshipRepository->getByUserCode($userCode);

            $userCodes = [$userCode];
            foreach ($friendships as $friendship) {

                $friend = $friendship->getUserAdded();
                if ($userCode === $friend) {

                    $friend = $friendship->getUserAdd();
                }

                $userCodes[] = $friend;
            }

            $announcements = $this->announcementRepository
                ->setPaginated($page, $limit)
                ->getBySearchAndUsers($search, $userCodes);

            $total = $this->announcementRepository->getPaginationTotal();


        } catch (\Throwable $throwable) {

            ~rt($throwable);
            $announcements = [];
        }

        $formattedAnnouncements = [];
        foreach ($announcements as $announcement) {

            $userCode = reset($announcement->getImpulses())->toArray()['owner'];

            $this->announcementRepository->fillImpulses($announcement);

            $announcementFormatted = $announcement->toArray();

            $announcementFormatted['sharedBy'] = $this->userRepository->getById($userCode)->toArray();

            $formattedAnnouncements[] = $announcementFormatted;
        }

        return Base\Response::create([
            'total' => $total,
            'items' => [
                'users' => $formattedUsers,
                'announcements' => $formattedAnnouncements,
            ]
        ], HttpStatusCode::OK());
    }
}
