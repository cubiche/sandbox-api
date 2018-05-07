<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order\ReadModel\Projection;

use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Reservation\Domain\Order\Event\OrderHasExpired;
use Sandbox\Reservation\Domain\Order\Event\OrderWasBooked;
use Sandbox\Reservation\Domain\Order\Event\OrderWasCompleted;
use Sandbox\Reservation\Domain\Order\Event\OrderWasCreated;
use Sandbox\Reservation\Domain\Order\Event\OrderWasRejected;
use Sandbox\Reservation\Domain\Order\OrderId;
use Sandbox\Reservation\Domain\Order\OrderState;
use Sandbox\Reservation\Domain\Order\ReadModel\Order;

/**
 * OrderProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * OrderProjector constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param OrderWasCreated $event
     */
    public function whenOrderWasCreated(OrderWasCreated $event)
    {
        $readModel = new Order(
            $event->orderId(),
            $event->userId(),
            $event->conferenceId(),
            $event->numberOfTickets()
        );

        $this->repository->persist($readModel);
    }

    /**
     * @param OrderWasBooked $event
     */
    public function whenOrderWasBooked(OrderWasBooked $event)
    {
        $readModel = $this->findOr404($event->orderId());
        $readModel->setState(OrderState::STATE_BOOKED());

        $this->repository->persist($readModel);
    }

    /**
     * @param OrderWasRejected $event
     */
    public function whenOrderWasRejected(OrderWasRejected $event)
    {
        $readModel = $this->findOr404($event->orderId());
        $readModel->setState(OrderState::STATE_REJECTED());

        $this->repository->persist($readModel);
    }

    /**
     * @param OrderHasExpired $event
     */
    public function whenOrderHasExpired(OrderHasExpired $event)
    {
        $readModel = $this->findOr404($event->orderId());
        $readModel->setState(OrderState::STATE_EXPIRED());

        $this->repository->persist($readModel);
    }

    /**
     * @param OrderWasCompleted $event
     */
    public function whenOrderWasCompleted(OrderWasCompleted $event)
    {
        $readModel = $this->findOr404($event->orderId());
        $readModel->setState(OrderState::STATE_COMPLETED());

        $this->repository->persist($readModel);
    }

    /**
     * @param OrderId $orderId
     *
     * @return Order
     */
    private function findOr404(OrderId $orderId)
    {
        /** @var Order $order */
        $order = $this->repository->get($orderId);
        if ($order === null) {
            throw new NotFoundException(sprintf(
                'There is no order with id: %s',
                $orderId
            ));
        }

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            OrderWasCreated::class => array('whenOrderWasCreated', 250),
            OrderWasBooked::class => array('whenOrderWasBooked', 250),
            OrderWasRejected::class => array('whenOrderWasRejected', 250),
            OrderHasExpired::class => array('whenOrderHasExpired', 250),
            OrderWasCompleted::class => array('whenOrderWasCompleted', 250),
        );
    }
}
