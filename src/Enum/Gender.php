<?php

namespace App\Enum;

use THS\Utils\Enum\Label;

/**
 * Enum de gêneros.
 *
 * @method Gender MALE()
 * @method Gender FEMALE()
 * @method Gender OTHER()
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
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
