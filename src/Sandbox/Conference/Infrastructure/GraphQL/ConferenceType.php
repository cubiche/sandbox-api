<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Infrastructure\GraphQL;

use Sandbox\Conference\Domain\ReadModel\Conference;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\DateType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * ConferenceType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->id()->toNative();
                },
            ])
            ->addField('name', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->name()->toNative();
                },
            ])
            ->addField('city', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->city()->toNative();
                },
            ])
            ->addField('country', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->country()->toNative();
                },
            ])
            ->addField('availableTickets', [
                'type' => new NonNullType(new IntType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->availableTickets()->toNative();
                },
            ])
            ->addField('price', [
                'type' => new NonNullType(new FloatType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->price()->amountToReal()->toNative();
                },
            ])
            ->addField('currency', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->price()->currency()->toNative();
                },
            ])
            ->addField('startAt', [
                'type' => new NonNullType(new DateType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->date()->from()->toNative();
                },
            ])
            ->addField('endAt', [
                'type' => new NonNullType(new DateType()),
                'resolve' => function (Conference $conference, $args) {
                    return $conference->date()->to()->toNative();
                },
            ])
        ;
    }
}
