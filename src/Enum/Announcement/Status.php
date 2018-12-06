<?php

namespace App\Enum\Announcement;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Enum de status do anúncio.
 *
 * @method static Status ACTIVE()
 * @method static Status INACTIVE()
 * @method static Status PAUSED()
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Status extends AbstractEnumeration
{
    /** @var string */
    const ACTIVE = 'active';

    /** @var string */
    const INACTIVE = 'inactive';

    /** @var string */
    const PAUSED = 'paused';
}
