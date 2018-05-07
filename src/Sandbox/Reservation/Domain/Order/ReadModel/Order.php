<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order\ReadModel;

use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\Order\OrderId;
use Sandbox\Reservation\Domain\Order\OrderState;
use Sandbox\Security\Domain\User\UserId;

/**
 * Order class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Order extends Entity implements ReadModelInterface
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
     * @var OrderState
     */
    protected $state;

    /**
     * Order constructor.
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
        $this->state = OrderState::STATE_NEW();
    }

    /**
     * @return OrderId
     */
    public function orderId()
    {
        return $this->id;
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

    /**
     * @return OrderState
     */
    public function state()
    {
        return $this->state;
    }

    /**
     * @param OrderState $state
     */
    public function setState(OrderState $state)
    {
        $this->state = $state;
    }
}
