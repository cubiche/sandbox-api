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

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\Command\AddPermissionToRoleCommand;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\Command\RemovePermissionFromRoleCommand;
use Sandbox\Security\Domain\Role\Role;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * RoleCommandHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleCommandHandlerTests extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function testCreateRole()
    {
        $this
            ->given($roleId = RoleId::next())
            ->and(
                $command = new CreateRoleCommand(
                    $roleId->toNative(),
                    'ROLE_NAME',
                    ['app']
                )
            )
            ->when($repository = $this->writeRepository(Role::class))
            ->then()
                ->variable($repository->get($roleId))
                    ->isNull()
                ->and()
                ->when($this->commandBus()->dispatch($command))
                ->then()
                    ->object($role = $repository->get($roleId))
                        ->isNotNull()
                        ->isInstanceOf(Role::class)
                    ->string($role->name()->toNative())
                        ->isEqualTo('ROLE_NAME')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testAddRemoveRolePermission()
    {
        $this
            ->given($roleId = RoleId::next())
            ->and(
                $command = new CreateRoleCommand(
                    $roleId->toNative(),
                    'ROLE_NAME'
                )
            )
            ->and($permissionOne = 'app.user.update')
            ->and($permissionTwo = 'app.user.create')
            ->and($repository = $this->writeRepository(Role::class))
            ->when($this->commandBus()->dispatch($command))
            ->then()
                ->object($role = $repository->get($roleId))
                    ->isInstanceOf(Role::class)
                ->integer($role->permissions()->count())
                    ->isEqualTo(0)
                ->exception(function () use ($roleId, $permissionOne) {
                    $this->commandBus()->dispatch(
                        new AddPermissionToRoleCommand(
                            $roleId,
                            $permissionOne
                        )
                    );
                })->isInstanceOf(NotFoundException::class)
                ->and()
                ->when(
                    $this->commandBus()->dispatch(
                        new AddPermissionToRoleCommand(
                            $roleId,
                            $permissionTwo
                        )
                    )
                )
                ->then()
                    ->object($role = $repository->get($roleId))
                        ->isInstanceOf(Role::class)
                    ->integer($role->permissions()->count())
                        ->isEqualTo(1)
                    ->and()
                    ->when(
                        $this->commandBus()->dispatch(
                            new RemovePermissionFromRoleCommand(
                                $roleId,
                                $permissionTwo
                            )
                        )
                    )
                    ->then()
                        ->object($role = $repository->get($roleId))
                            ->isInstanceOf(Role::class)
                        ->integer($role->permissions()->count())
                            ->isEqualTo(0)
                        ->exception(function () use ($permissionOne) {
                            $this->commandBus()->dispatch(
                                new RemovePermissionFromRoleCommand(
                                    RoleId::next()->toNative(),
                                    $permissionOne
                                )
                            );
                        })->isInstanceOf(NotFoundException::class)
        ;
    }
}
