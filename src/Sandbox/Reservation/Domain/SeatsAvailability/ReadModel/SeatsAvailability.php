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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\SeatsAvailability\ReservationId;

/**
 * SeatsAvailability class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailability extends Entity implements ReadModelInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $reservations;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $availableSeats;

    /**
     * SeatsAvailability constructor.
     *
     * @param ConferenceId $conferenceId
     * @param Integer      $availableSeats
     */
    public function __construct(
        ConferenceId $conferenceId,
        Integer $availableSeats
    ) {
        parent::__construct($conferenceId);

        $this->availableSeats = $availableSeats;
        $this->reservations = new ArrayHashMap();
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return ArrayHashMap
     */
    public function reservations()
    {
        return $this->reservations;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function availableSeats()
    {
        return $this->availableSeats;
    }

    /**
     * @param ReservationId $reservationId
     * @param Integer       $numberOfSeats
     */
    public function addReservation(ReservationId $reservationId, Integer $numberOfSeats)
    {
        $this->availableSeats = $this->availableSeats->sub($numberOfSeats);
        $this->reservations->set($reservationId->toNative(), $numberOfSeats);
    }

    /**
     * @param ReservationId $reservationId
     */
    public function removeReservation(ReservationId $reservationId)
    {
        $numberOfSeats = $this->reservations->get($reservationId->toNative());

        $this->availableSeats = $this->availableSeats->add($numberOfSeats);
        $this->reservations->removeAt($reservationId->toNative());
    }

    /**
     * @param ReservationId $reservationId
     */
    public function commitReservation(ReservationId $reservationId)
    {
        $this->reservations->removeAt($reservationId->toNative());
    }
}
