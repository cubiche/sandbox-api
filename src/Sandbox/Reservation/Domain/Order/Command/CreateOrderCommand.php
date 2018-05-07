<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * CreateOrderCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateOrderCommand extends Command
{
    /**
     * @var string
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $conferenceId;

    /**
     * @var int
     */
    protected $numberOfTickets;

    /**
     * CreateOrderCommand constructor.
     *
     * @param string $orderId
     * @param string $userId
     * @param string $conferenceId
     * @param int    $numberOfTickets
     */
    public function __construct(
        $orderId,
        $userId,
        $conferenceId,
        $numberOfTickets
    ) {
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->conferenceId = $conferenceId;
        $this->numberOfTickets = $numberOfTickets;
    }

    /**
     * @return string
     */
    public function orderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return int
     */
    public function numberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('orderId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('userId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('conferenceId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('numberOfTickets', Assertion::integer()->notBlank());
    }
}
