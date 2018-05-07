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
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\Order\OrderId;
use Sandbox\Security\Domain\User\UserId;

/**
 * OrderWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderWasCreated extends DomainEvent
{
    /**
     * @var UserId
     */
    protected $userId;

    /**
     * @var ConferenceId
     */
    protected $conferenceId;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfTickets;

    /**
     * OrderWasCreated constructor.
     *
     * @param OrderId      $orderId
     * @param UserId       $userId
     * @param ConferenceId $conferenceId
     * @param Integer      $numberOfTickets
     */
    public function __construct(
        OrderId $orderId,
        UserId $userId,
        ConferenceId $conferenceId,
        Integer $numberOfTickets
    ) {
        parent::__construct($orderId);

        $this->userId = $userId;
        $this->conferenceId = $conferenceId;
        $this->numberOfTickets = $numberOfTickets;
    }

    /**
     * @return OrderId
     */
    public function orderId()
    {
        return $this->aggregateId();
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function numberOfTickets()
    {
        return $this->numberOfTickets;
    }
}
