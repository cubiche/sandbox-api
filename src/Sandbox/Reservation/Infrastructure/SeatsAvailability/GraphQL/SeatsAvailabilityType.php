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

use Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\SeatsAvailability;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;

/**
 * SeatsAvailabilityType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (SeatsAvailability $seatsAvailability, $args) {
                    return $seatsAvailability->id()->toNative();
                },
            ])
            ->addField('reservations', [
                'type' => new ListType(new ReservationType()),
                'resolve' => function (SeatsAvailability $seatsAvailability, $args) {
                    return $seatsAvailability->reservations()->toArray();
                },
            ])
            ->addField('availableSeats', [
                'type' => new NonNullType(new IntType()),
                'resolve' => function (SeatsAvailability $seatsAvailability, $args) {
                    return $seatsAvailability->availableSeats()->toNative();
                },
            ])
        ;
    }
}
