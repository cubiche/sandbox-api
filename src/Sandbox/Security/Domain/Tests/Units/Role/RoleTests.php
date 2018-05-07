<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role;

use Sandbox\Security\Domain\Role\Role;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Cubiche\Domain\System\StringLiteral;

/**
 * RoleTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleTests extends TestCase
{
    /**
     * @param RoleId $id
     * @param string $name
     * @param array  $permissions
     *
     * @return Role
     */
    protected function createRole($id, $name, $permissions = [])
    {
        return new Role($id, StringLiteral::fromNative($name), $permissions);
    }

    /**
     * {@inheritdoc}
     */
    public function testPropertiesShouldBeFillingProperly()
    {
        $this
            ->given($roleId = RoleId::next())
            ->and($name = $this->faker->sentence())
            ->when($role = $this->createRole($roleId, $name, [StringLiteral::fromNative('app')]))
            ->then()
                ->string($role->name()->toNative())
                    ->isEqualTo($name);
    }

    /**
     * {@inheritdoc}
     */
    public function testPermissions()
    {
        $this
            ->given($roleId = RoleId::next())
            ->and($name = $this->faker->sentence())
            ->when($role = $this->createRole($roleId, $name))
            ->then()
                ->integer(count($role->permissions()))
                    ->isEqualTo(0)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testAddRemovePermissions()
    {
        $this
            ->given($roleId = RoleId::next())
            ->and($name = $this->faker->sentence())
            ->and($permissionOne = StringLiteral::fromNative('app.user.create'))
            ->when($role = $this->createRole($roleId, $name))
            ->then()
                ->integer(count($role->permissions()))
                    ->isEqualTo(0)
                ->and()
                ->when($role->addPermission($permissionOne))
                ->then()
                    ->integer(count($role->permissions()))
                        ->isEqualTo(1)
                    ->and()
                    ->when($role->removePermission(StringLiteral::fromNative('app.user.list')))
                    ->then()
                        ->integer(count($role->permissions()))
                            ->isEqualTo(1)
                        ->and()
                        ->when($role->removePermission($permissionOne))
                        ->then()
                            ->integer(count($role->permissions()))
                                ->isEqualTo(0)
        ;
    }
}
