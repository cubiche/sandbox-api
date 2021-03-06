<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Sandbox\Reservation\Domain\Order\OrderId;

/**
 * OrderWasRejected class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderWasRejected extends DomainEvent
{
    /**
     * OrderWasBooked constructor.
     *
     * @param OrderId $orderId
     */
    public function __construct(OrderId $orderId)
    {
        parent::__construct($orderId);
    }

    /**
     * @return OrderId
     */
    public function orderId()
    {
        return $this->aggregateId();
    }
}
