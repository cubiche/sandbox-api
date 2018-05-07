<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\SeatsAvailability\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\SeatsAvailability\ReservationId;

/**
 * ReservationWasAccepted class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ReservationWasAccepted extends DomainEvent
{
    /**
     * @var ReservationId
     */
    protected $reservationId;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfSeats;

    /**
     * ReservationWasAccepted constructor.
     *
     * @param ConferenceId  $conferenceId
     * @param ReservationId $reservationId
     * @param Integer       $numberOfSeats
     */
    public function __construct(
        ConferenceId $conferenceId,
        ReservationId $reservationId,
        Integer $numberOfSeats
    ) {
        parent::__construct($conferenceId);

        $this->reservationId = $reservationId;
        $this->numberOfSeats = $numberOfSeats;
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->aggregateId();
    }

    /**
     * @return ReservationId
     */
    public function reservationId()
    {
        return $this->reservationId;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function numberOfSeats()
    {
        return $this->numberOfSeats;
    }
}
