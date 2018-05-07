<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\User;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Sandbox\Security\Domain\User\ReadModel\User;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * UserType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (User $user, $args) {
                    return $user->userId()->toNative();
                },
            ])
            ->addField('username', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (User $user, $args) {
                    return $user->username()->toNative();
                },
            ])
            ->addField('email', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (User $user, $args) {
                    return $user->email()->toNative();
                },
            ])
            ->addField('permissions', [
                'type' => new ListType(new StringType()),
                'resolve' => function (User $user, $args) {
                    return $user->permissions()->toArray();
                },
            ])
            ->addField('verified', [
                'type' => new NonNullType(new BooleanType()),
                'resolve' => function (User $user, $args) {
                    return $user->isVerified();
                },
            ])
            ->addField('enabled', [
                'type' => new NonNullType(new BooleanType()),
                'resolve' => function (User $user, $args) {
                    return $user->isEnabled();
                },
            ])
        ;
    }
}
