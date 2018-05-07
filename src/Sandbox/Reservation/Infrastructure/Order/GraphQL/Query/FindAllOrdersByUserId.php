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
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;

/**
 * FindAllOrdersByUserId class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllOrdersByUserId extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('userId', new NonNullType(new IdType()))
        ;
    }

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

        return $controller->findAllByUserIdAction($args['userId']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ordersByUserId';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new OrderType());
    }
}
