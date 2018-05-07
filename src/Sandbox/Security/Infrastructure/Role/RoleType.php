<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\Role;

use Sandbox\Security\Domain\Role\ReadModel\Role;
use Cubiche\Domain\System\StringLiteral;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * RoleType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (Role $role, $args) {
                    return $role->roleId()->toNative();
                },
            ])
            ->addField('name', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Role $role, $args) {
                    return $role->name()->toNative();
                },
            ])
            ->addField('permissions', [
                'type' => new ListType(new StringType()),
                'resolve' => function (Role $role, $args) {
                    $permissions = [];
                    /** @var StringLiteral $permission */
                    foreach ($role->permissions()->toArray() as $permission) {
                        $permissions[] = $permission->toNative();
                    }

                    return $permissions;
                },
            ])
        ;
    }
}
