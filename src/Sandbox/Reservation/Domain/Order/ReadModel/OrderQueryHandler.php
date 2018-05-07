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

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Sandbox\Reservation\Domain\Order\OrderId;
use Sandbox\Reservation\Domain\Order\ReadModel\Query\FindAllOrders;
use Sandbox\Reservation\Domain\Order\ReadModel\Query\FindAllOrdersByUserId;
use Sandbox\Reservation\Domain\Order\ReadModel\Query\FindOneOrderById;
use Sandbox\Security\Domain\User\UserId;

/**
 * OrderQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * OrderQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindAllOrders $query
     *
     * @return CollectionInterface|Order[]
     */
    public function findAllOrders(FindAllOrders $query)
    {
        return $this->repository->getIterator();
    }

    /**
     * @param FindAllOrdersByUserId $query
     *
     * @return CollectionInterface|Order[]
     */
    public function findAllOrdersByUserId(FindAllOrdersByUserId $query)
    {
        return $this->repository->find(
            Criteria::property('userId')->eq(UserId::fromNative($query->userId()))
        );
    }

    /**
     * @param FindOneOrderById $query
     *
     * @return Order|null
     */
    public function findOneOrderById(FindOneOrderById $query)
    {
        return $this->repository->findOne(
            Criteria::property('id')->eq(OrderId::fromNative($query->orderId()))
        );
    }
}
