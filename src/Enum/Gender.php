<?php

namespace App\Enum;

use THS\Utils\Enum\Label;

/**
 * Enum de gêneros.
 *
 * @method static Gender MALE()
 * @method static Gender FEMALE()
 * @method static Gender OTHER()
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Gender extends Label
{
    /** @var string */
    const MALE = 'male';

    /** @var string */
    const FEMALE = 'female';

    /** @var string */
    const OTHER = 'other';

    /**
     * @inheritDoc
     */
    protected function getLabels()
    {
        return [
            self::MALE => 'Masculino',
            self::FEMALE => 'Feminino',
            self::OTHER => 'Outro',
        ];
    }
}
