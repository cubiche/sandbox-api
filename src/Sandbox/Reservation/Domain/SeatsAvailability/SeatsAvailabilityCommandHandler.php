<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\SeatsAvailability;

use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Reservation\Domain\SeatsAvailability\Command\CancelSeatReservationCommand;
use Sandbox\Reservation\Domain\SeatsAvailability\Command\CommitSeatReservationCommand;
use Sandbox\Reservation\Domain\SeatsAvailability\Command\CreateSeatsAvailabilityCommand;
use Sandbox\Reservation\Domain\SeatsAvailability\Command\MakeSeatReservationCommand;

/**
 * SeatsAvailabilityCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * SeatsAvailabilityCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateSeatsAvailabilityCommand $command
     */
    public function createSeatsAvailability(CreateSeatsAvailabilityCommand $command)
    {
        $seatsAvailability = new SeatsAvailability(
            ConferenceId::fromNative($command->conferenceId()),
            Integer::fromNative($command->numberOfSeats())
        );

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param MakeSeatReservationCommand $command
     */
    public function makeSeatReservation(MakeSeatReservationCommand $command)
    {
        $seatsAvailability = $this->findOr404($command->conferenceId());

        $seatsAvailability->makeReservation(
            ReservationId::fromNative($command->reservationId()),
            Integer::fromNative($command->numberOfSeats())
        );

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param CancelSeatReservationCommand $command
     */
    public function cancelSeatReservation(CancelSeatReservationCommand $command)
    {
        $seatsAvailability = $this->findOr404($command->conferenceId());
        $seatsAvailability->cancelReservation(ReservationId::fromNative($command->reservationId()));

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param CommitSeatReservationCommand $command
     */
    public function commitSeatReservation(CommitSeatReservationCommand $command)
    {
        $seatsAvailability = $this->findOr404($command->conferenceId());
        $seatsAvailability->commitReservation(ReservationId::fromNative($command->reservationId()));

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param string $conferenceId
     *
     * @return SeatsAvailability
     */
    private function findOr404($conferenceId)
    {
        $seatsAvailability = $this->repository->get(ConferenceId::fromNative($conferenceId));
        if ($seatsAvailability === null) {
            throw new NotFoundException(sprintf(
                'There is no seatsAvailability with id: %s',
                $conferenceId
            ));
        }

        return $seatsAvailability;
    }
}
