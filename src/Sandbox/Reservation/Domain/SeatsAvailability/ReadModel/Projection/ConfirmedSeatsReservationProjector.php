<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\Projection;

use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Sandbox\Reservation\Domain\SeatsAvailability\Event\ReservationWasCommitted;
use Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\ConfirmedSeatsReservation;

/**
 * ConfirmedSeatsReservationProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConfirmedSeatsReservationProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * ConfirmedSeatsReservationProjector constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ReservationWasCommitted $event
     */
    public function whenReservationWasCommitted(ReservationWasCommitted $event)
    {
        $readModel = new ConfirmedSeatsReservation(
            $event->reservationId(),
            $event->conferenceId(),
            $event->numberOfSeats()
        );

        $this->repository->persist($readModel);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ReservationWasCommitted::class => array('whenReservationWasCommitted', 250),
        );
    }
}
