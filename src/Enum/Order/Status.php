<?php

namespace App\Enum\Order;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Enum de status do pedido.
 *
 * @method static Status PENDING()
 * @method static Status APPROVED()
 * @method static Status INVOICED()
 * @method static Status SHIPPED()
 * @method static Status CANCELLED()
 * @method static Status DELIVERED()
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Status extends AbstractEnumeration
{
    /** @var string */
    const PENDING = 'pending';

    /** @var string */
    const APPROVED = 'approved';

    /** @var string */
    const INVOICED = 'invoiced';

    /** @var string */
    const SHIPPED = 'shipped';

    /** @var string */
    const CANCELLED = 'cancelled';

    /** @var string */
    const DELIVERED = 'delivered';
}
