<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order;

use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\Order\Event\OrderHasExpired;
use Sandbox\Reservation\Domain\Order\Event\OrderWasBooked;
use Sandbox\Reservation\Domain\Order\Event\OrderWasCompleted;
use Sandbox\Reservation\Domain\Order\Event\OrderWasCreated;
use Sandbox\Reservation\Domain\Order\Event\OrderWasRejected;
use Sandbox\Security\Domain\User\UserId;

/**
 * Order class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Order extends AggregateRoot
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

        $this->recordAndApplyEvent(
            new OrderWasCreated($orderId, $userId, $conferenceId, $numberOfTickets)
        );
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
     * Mark order as booked.
     */
    public function markAsBooked()
    {
        if ($this->state->toNative() !== OrderState::STATE_NEW) {
            throw new \LogicException(sprintf('You are trying to book an order with an invalid state: %s', $this->state));
        }

        $this->recordAndApplyEvent(new OrderWasBooked($this->orderId()));
    }

    /**
     * Reject the order.
     */
    public function reject()
    {
        if ($this->state->toNative() !== OrderState::STATE_EXPIRED) {
            if (!in_array($this->state->toNative(), [OrderState::STATE_NEW, OrderState::STATE_BOOKED], true)) {
                throw new \LogicException(
                    sprintf('You are trying to reject an order with an invalid state: %s', $this->state)
                );
            }

            $this->recordAndApplyEvent(new OrderWasRejected($this->orderId()));
        }
    }

    /**
     * Expire the order.
     */
    public function expire()
    {
        if (!in_array($this->state->toNative(), [OrderState::STATE_NEW, OrderState::STATE_BOOKED], true)) {
            throw new \LogicException(
                sprintf('You are trying to expire an order with an invalid state: %s', $this->state)
            );
        }

        $this->recordAndApplyEvent(new OrderHasExpired($this->orderId()));
    }

    /**
     * Complete the order.
     */
    public function complete()
    {
        if ($this->state->toNative() !== OrderState::STATE_BOOKED) {
            throw new \LogicException(
                sprintf('You are trying to complete an order with an invalid state: %s', $this->state)
            );
        }

        $this->recordAndApplyEvent(new OrderWasCompleted($this->orderId()));
    }

    /**
     * @param OrderWasCreated $event
     */
    protected function applyOrderWasCreated(OrderWasCreated $event)
    {
        $this->userId = $event->userId();
        $this->conferenceId = $event->conferenceId();
        $this->numberOfTickets = $event->numberOfTickets();
        $this->state = OrderState::STATE_NEW();
    }

    /**
     * @param OrderWasBooked $event
     */
    protected function applyOrderWasBooked(OrderWasBooked $event)
    {
        $this->state = OrderState::STATE_BOOKED();
    }

    /**
     * @param OrderWasRejected $event
     */
    protected function applyOrderWasRejected(OrderWasRejected $event)
    {
        $this->state = OrderState::STATE_REJECTED();
    }

    /**
     * @param OrderHasExpired $event
     */
    protected function applyOrderHasExpired(OrderHasExpired $event)
    {
        $this->state = OrderState::STATE_EXPIRED();
    }

    /**
     * @param OrderWasCompleted $event
     */
    protected function applyOrderWasCompleted(OrderWasCompleted $event)
    {
        $this->state = OrderState::STATE_COMPLETED();
    }
}
