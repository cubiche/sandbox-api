<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Application\Order\ReadModel\Controller;

use Cubiche\Core\Collections\CollectionInterface;
use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\Core\Application\Controller\UserAwareTrait;
use Sandbox\Reservation\Domain\Order\ReadModel\Order;
use Sandbox\Reservation\Domain\Order\ReadModel\Query\FindAllOrders;
use Sandbox\Reservation\Domain\Order\ReadModel\Query\FindAllOrdersByUserId;
use Sandbox\Reservation\Domain\Order\ReadModel\Query\FindOneOrderById;

/**
 * OrderController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderController extends QueryController
{
    use UserAwareTrait;

    /**
     * @return CollectionInterface|Order[]
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(new FindAllOrders());
    }

    /**
     * @param string $userId
     *
     * @return CollectionInterface|Order[]
     */
    public function findAllByUserIdAction($userId)
    {
        return $this->queryBus()->dispatch(new FindAllOrdersByUserId($userId));
    }

    /**
     * @return CollectionInterface|Order[]
     */
    public function findAllByCurrentUserAction()
    {
        $userId = $this->findCurrentUserOr401();

        return $this->queryBus()->dispatch(new FindAllOrdersByUserId($userId));
    }

    /**
     * @param string $orderId
     *
     * @return Order|null
     */
    public function findOneByIdAction($orderId)
    {
        return $this->queryBus()->dispatch(new FindOneOrderById($orderId));
    }
}
