<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Payment\Application\Controller;

use Cubiche\Core\Validator\Assert;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\Payment\Domain\Event\PaymentWasReceived;
use Sandbox\Reservation\Domain\Order\OrderId;

/**
 * PaymentController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PaymentController extends CommandController
{
    /**
     * @param string $orderId
     *
     * @return string
     */
    public function payAction($orderId)
    {
        Assert::uuid($orderId);

        DomainEventPublisher::publish(new PaymentWasReceived(OrderId::fromNative($orderId)));
    }
}
