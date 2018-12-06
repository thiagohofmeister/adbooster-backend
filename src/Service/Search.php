<?php

namespace App\Service;

use App\Exception\Repository\DataNotFoundException;
use App\Service\Base\Service\Contract;
use THS\Utils\Date;
use THS\Utils\Enum\HttpStatusCode;

/**
 * @todo Document class Search.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
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
                ->getBySearchAndFriends($search, $friends);

            $total = $this->announcementRepository->getPaginationTotal();


        } catch (\Throwable $throwable) {

            $announcements = [];
        }

        $formattedAnnouncements = [];
        foreach ($announcements as $announcement) {

            $impulse = reset($announcement->getImpulses());

            $this->announcementRepository->fillImpulses($announcement);

            $announcementFormatted = $announcement->toArray();

            $announcementFormatted['sharedBy'] = $this->userRepository->getById($impulse->getOwner())->toArray();
            $announcementFormatted['impulseDate'] = $impulse->getCreated()->format(Date::JAVASCRIPT_ISO_FORMAT);

            $formattedAnnouncements[] = $announcementFormatted;
        }

        if (empty($formattedUsers) && empty($formattedAnnouncements)) {
            throw new \Exception('Nenhum resultado encontrado.', HttpStatusCode::NOT_FOUND);
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
