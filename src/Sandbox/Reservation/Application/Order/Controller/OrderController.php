<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Application\Order\Controller;

use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\Core\Application\Controller\UserAwareTrait;
use Sandbox\Reservation\Domain\Order\Command\CreateOrderCommand;
use Sandbox\Reservation\Domain\Order\Command\ExpireOrderCommand;
use Sandbox\Reservation\Domain\Order\OrderId;

/**
 * OrderController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderController extends CommandController
{
    use UserAwareTrait;

    /**
     * @param string $conferenceId
     * @param int    $numberOfTickets
     *
     * @return string
     */
    public function createAction($conferenceId, $numberOfTickets)
    {
        $userId = $this->findCurrentUserOr401();
        $orderId = OrderId::next()->toNative();

        $this->commandBus()->dispatch(
            new CreateOrderCommand($orderId, $userId, $conferenceId, $numberOfTickets)
        );

        return $orderId;
    }

    /**
     * @param string $orderId
     *
     * @return bool
     */
    public function expireAction($orderId)
    {
        $this->commandBus()->dispatch(
            new ExpireOrderCommand($orderId)
        );

        return true;
    }
}
