<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\SeatsAvailability\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * CancelSeatReservationCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CancelSeatReservationCommand extends Command
{
    /**
     * @var string
     */
    protected $conferenceId;

    /**
     * @var string
     */
    protected $reservationId;

    /**
     * CancelSeatReservationCommand constructor.
     *
     * @param string $conferenceId
     * @param string $reservationId
     */
    public function __construct(
        $conferenceId,
        $reservationId
    ) {
        $this->conferenceId = $conferenceId;
        $this->reservationId = $reservationId;
    }

    /**
     * @return string
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return string
     */
    public function reservationId()
    {
        return $this->reservationId;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('conferenceId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('reservationId', Assertion::uuid()->notBlank());
    }
}
