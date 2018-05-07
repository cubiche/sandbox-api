<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order;

use Cubiche\Domain\System\Enum;

/**
 * OrderState class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 *
 * @method static OrderState STATE_NEW()
 * @method static OrderState STATE_BOOKED()
 * @method static OrderState STATE_REJECTED()
 * @method static OrderState STATE_EXPIRED()
 * @method static OrderState STATE_COMPLETED()
 */
class OrderState extends Enum
{
    const STATE_NEW = 'new';
    const STATE_BOOKED = 'booked';
    const STATE_REJECTED = 'rejected';
    const STATE_EXPIRED = 'expired';
    const STATE_COMPLETED = 'completed';
}
