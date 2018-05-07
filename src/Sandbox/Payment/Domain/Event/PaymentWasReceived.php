<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Payment\Domain\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Sandbox\Reservation\Domain\Order\OrderId;

/**
 * PaymentWasReceived class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PaymentWasReceived extends DomainEvent
{
    /**
     * PaymentWasReceived constructor.
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
