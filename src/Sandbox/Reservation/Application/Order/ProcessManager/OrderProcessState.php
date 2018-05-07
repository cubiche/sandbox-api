<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Application\Order\ProcessManager;

use Cubiche\Domain\ProcessManager\ProcessManagerState;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\Order\OrderId;
use Cubiche\Domain\System\StringLiteral;

/**
 * OrderProcessStates class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class OrderProcessState extends ProcessManagerState
{
    /**
     * @var ConferenceId
     */
    protected $conferenceId;

    /**
     * OrderProcessState constructor.
     *
     * @param OrderId      $orderId
     * @param ConferenceId $conferenceId
     */
    public function __construct(OrderId $orderId, ConferenceId $conferenceId)
    {
        parent::__construct(
            $orderId,
            StringLiteral::fromNative(OrderProcessStates::AWAITING_RESERVATION_CONFIRMATION)
        );

        $this->conferenceId = $conferenceId;
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }
}
