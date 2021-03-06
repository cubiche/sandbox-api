<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Infrastructure\Order\GraphQL\Query;

use Sandbox\Reservation\Application\Order\ReadModel\Controller\OrderController;
use Sandbox\Reservation\Domain\Order\ReadModel\Order;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\OrderType;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;

/**
 * FindAllOrders class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllOrders extends AbstractField
{
    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Order[]
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var OrderController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.order');

        return $controller->findAllAction();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new OrderType());
    }
}
