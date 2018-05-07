<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\SeatsAvailability\ReadModel;

use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\SeatsAvailability\ReservationId;

/**
 * ConfirmedSeatsReservation class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConfirmedSeatsReservation extends Entity implements ReadModelInterface
{
    /**
     * @var ReservationId
     */
    protected $reservationId;

    /**
     * @var ConferenceId
     */
    protected $conferenceId;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfSeats;

    /**
     * ConfirmedSeatsReservation constructor.
     *
     * @param ReservationId $reservationId
     * @param ConferenceId  $conferenceId
     * @param Integer       $numberOfSeats
     */
    public function __construct(
        ReservationId $reservationId,
        ConferenceId $conferenceId,
        Integer $numberOfSeats
    ) {
        parent::__construct($reservationId);

        $this->conferenceId = $conferenceId;
        $this->numberOfSeats = $numberOfSeats;
    }

    /**
     * @return ReservationId
     */
    public function reservationId()
    {
        return $this->reservationId;
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
    public function numberOfSeats()
    {
        return $this->numberOfSeats;
    }
}
