<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Infrastructure\SeatsAvailability\GraphQL;

use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;

/**
 * ReservationType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ReservationType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('reservationId', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (array $reservation, $args) {
                    return $reservation['reservationId'];
                },
            ])
            ->addField('numberOfSeats', [
                'type' => new NonNullType(new IntType()),
                'resolve' => function (array $reservation, $args) {
                    return $reservation['numberOfSeats']->toNative();
                },
            ])
        ;
    }
}
