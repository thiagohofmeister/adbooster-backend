<?php

namespace App\Enum\Order;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Enum de formas de pagamento do pedido.
 *
 * @method static PaymentMethod WALLET()
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class PaymentMethod extends AbstractEnumeration
{
    /** @var string */
    const WALLET = 'wallet';
}
