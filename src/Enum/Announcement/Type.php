<?php

namespace App\Enum\Announcement;

use THS\Utils\Enum\Label;

/**
 * Enum dos tipos de anúncio disponíveis.
 *
 * @method static Type BRONZE()
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Type extends Label
{
    /** @var string */
    const BRONZE = 'bronze';

    /**
     * @inheritDoc
     */
    protected function getLabels()
    {
        return [
            self::BRONZE => 'Bronze'
        ];
    }
}
