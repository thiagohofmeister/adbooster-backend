<?php

namespace App\Service;

use App\Service\Base\Service\Contract;
use App\Service\Base;

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
     * Retorna os anúncios pelo usuário e seus amigos.
     *
     * @param string $userCode
     */
    public function retrieveByUser(string $userCode)
    {
        $announcements = $this->announcementRepository->getByUser($userCode);

        $announcementsFormatted = [];
        foreach ($announcements as $announcement) {
            $announcementsFormatted[] = $announcement->toArray();
        }

        ~rt($announcementsFormatted);
    }
}
