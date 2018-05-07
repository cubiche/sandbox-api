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
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Reservation\Domain\SeatsAvailability\Event\ReservationWasAccepted;
use Sandbox\Reservation\Domain\SeatsAvailability\Event\ReservationWasCancelled;
use Sandbox\Reservation\Domain\SeatsAvailability\Event\ReservationWasCommitted;
use Sandbox\Reservation\Domain\SeatsAvailability\Event\SeatsAvailabilityWasCreated;
use Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\SeatsAvailability;

/**
 * SeatsAvailabilityProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * Projector constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param SeatsAvailabilityWasCreated $event
     */
    public function whenSeatsAvailabilityWasCreated(SeatsAvailabilityWasCreated $event)
    {
        $readModel = new SeatsAvailability(
            $event->conferenceId(),
            $event->numberOfSeats()
        );

        $this->repository->persist($readModel);
    }

    /**
     * @param ReservationWasAccepted $event
     */
    public function whenReservationWasAccepted(ReservationWasAccepted $event)
    {
        $readModel = $this->findOr404($event->conferenceId());
        $readModel->addReservation($event->reservationId(), $event->numberOfSeats());

        $this->repository->persist($readModel);
    }

    /**
     * @param ReservationWasCancelled $event
     */
    public function whenReservationWasCancelled(ReservationWasCancelled $event)
    {
        $readModel = $this->findOr404($event->conferenceId());
        $readModel->removeReservation($event->reservationId());

        $this->repository->persist($readModel);
    }

    /**
     * @param ReservationWasCommitted $event
     */
    public function whenReservationWasCommitted(ReservationWasCommitted $event)
    {
        $readModel = $this->findOr404($event->conferenceId());
        $readModel->commitReservation($event->reservationId());

        $this->repository->persist($readModel);
    }

    /**
     * @param ConferenceId $conferenceId
     *
     * @return SeatsAvailability
     */
    private function findOr404(ConferenceId $conferenceId)
    {
        /** @var SeatsAvailability $seatsAvailability */
        $seatsAvailability = $this->repository->get($conferenceId);
        if ($seatsAvailability === null) {
            throw new NotFoundException(sprintf(
                'There is no seatsAvailability with id: %s',
                $conferenceId
            ));
        }

        return $seatsAvailability;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            SeatsAvailabilityWasCreated::class => array('whenSeatsAvailabilityWasCreated', 250),
            ReservationWasAccepted::class => array('whenReservationWasAccepted', 250),
            ReservationWasCancelled::class => array('whenReservationWasCancelled', 250),
            ReservationWasCommitted::class => array('whenReservationWasCommitted', 250),
        );
    }
}
