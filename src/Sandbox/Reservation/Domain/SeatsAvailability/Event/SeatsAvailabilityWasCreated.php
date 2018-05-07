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
use Sandbox\Conference\Domain\ConferenceId;
use Cubiche\Domain\System\Integer;

/**
 * SeatsAvailabilityWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityWasCreated extends DomainEvent
{
    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfSeats;

    /**
     * SeatsAvailabilityWasCreated constructor.
     *
     * @param ConferenceId $conferenceId
     * @param Integer      $numberOfSeats
     */
    public function __construct(
        ConferenceId $conferenceId,
        Integer $numberOfSeats
    ) {
        parent::__construct($conferenceId);

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
     * @return \Cubiche\Domain\System\Integer
     */
    public function numberOfSeats()
    {
        return $this->numberOfSeats;
    }
}
