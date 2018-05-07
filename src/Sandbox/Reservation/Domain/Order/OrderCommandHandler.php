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

use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Reservation\Domain\Order\Command\CompleteOrderCommand;
use Sandbox\Reservation\Domain\Order\Command\CreateOrderCommand;
use Sandbox\Reservation\Domain\Order\Command\ExpireOrderCommand;
use Sandbox\Reservation\Domain\Order\Command\MarkOrderAsBookedCommand;
use Sandbox\Reservation\Domain\Order\Command\RejectOrderCommand;
use Sandbox\Security\Domain\User\UserId;

/**
 * OrderCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * OrderCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateOrderCommand $command
     */
    public function createOrder(CreateOrderCommand $command)
    {
        $order = new Order(
            OrderId::fromNative($command->orderId()),
            UserId::fromNative($command->userId()),
            ConferenceId::fromNative($command->conferenceId()),
            Integer::fromNative($command->numberOfTickets())
        );

        $this->repository->persist($order);
    }

    /**
     * @param MarkOrderAsBookedCommand $command
     */
    public function markOrderAsBooked(MarkOrderAsBookedCommand $command)
    {
        $order = $this->findOr404($command->orderId());
        $order->markAsBooked();

        $this->repository->persist($order);
    }

    /**
     * @param RejectOrderCommand $command
     */
    public function rejectOrder(RejectOrderCommand $command)
    {
        $order = $this->findOr404($command->orderId());
        $order->reject();

        $this->repository->persist($order);
    }

    /**
     * @param ExpireOrderCommand $command
     */
    public function expireOrder(ExpireOrderCommand $command)
    {
        $order = $this->findOr404($command->orderId());
        $order->expire();

        $this->repository->persist($order);
    }

    /**
     * @param CompleteOrderCommand $command
     */
    public function completeOrder(CompleteOrderCommand $command)
    {
        $order = $this->findOr404($command->orderId());
        $order->complete();

        $this->repository->persist($order);
    }

    /**
     * @param string $orderId
     *
     * @return Order
     */
    private function findOr404($orderId)
    {
        $order = $this->repository->get(OrderId::fromNative($orderId));
        if ($order === null) {
            throw new NotFoundException(sprintf(
                'There is no order with id: %s',
                $orderId
            ));
        }

        return $order;
    }
}
