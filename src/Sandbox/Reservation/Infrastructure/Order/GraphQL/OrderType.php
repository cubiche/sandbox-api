<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Infrastructure\Order\GraphQL;

use Sandbox\Reservation\Domain\Order\ReadModel\Order;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * OrderType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class OrderType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (Order $order, $args) {
                    return $order->id()->toNative();
                },
            ])
            ->addField('conferenceId', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Order $order, $args) {
                    return $order->conferenceId()->toNative();
                },
            ])
            ->addField('conference', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Order $order, $args, ResolveInfo $info) {
                    /** @var SeatsAvailabilityController $controller */
                    $controller = $info->getContainer()->get('app.controller.read_model.conference');
                    $conference = $controller->findOneByIdAction($order->conferenceId()->toNative());

                    return $conference->name()->toNative();
                },
            ])
            ->addField('numberOfTickets', [
                'type' => new NonNullType(new IntType()),
                'resolve' => function (Order $order, $args) {
                    return $order->numberOfTickets()->toNative();
                },
            ])
            ->addField('state', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Order $order, $args) {
                    return $order->state()->toNative();
                },
            ])
        ;
    }
}
