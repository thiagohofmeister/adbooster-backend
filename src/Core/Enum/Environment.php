<?php

namespace App\Core\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Ambiente do sistema.
 *
 * @method static Environment DEVELOPMENT()
 * @method static Environment PRODUCTION()
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Environment extends AbstractEnumeration
{
    /** @var string Ambiente de desenvolvimento. */
    const DEVELOPMENT = 'development';

    /** @var string Ambiente de produção. */
    const PRODUCTION = 'production';
}
