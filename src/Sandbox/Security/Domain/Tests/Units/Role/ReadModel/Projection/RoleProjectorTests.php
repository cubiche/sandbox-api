<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role\ReadModel\Projection;

use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\Command\AddPermissionToRoleCommand;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\Command\RemovePermissionFromRoleCommand;
use Sandbox\Security\Domain\Role\ReadModel\Projection\RoleProjector;
use Sandbox\Security\Domain\Role\ReadModel\Role;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * RoleProjectorTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleProjectorTests extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function testCreate()
    {
        $this
            ->given(
                $projector = new RoleProjector(
                    $this->queryRepository(Role::class),
                    $this->permissionRepository()
                )
            )
            ->then()
                ->array($projector->getSubscribedEvents())
                    ->isNotEmpty()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testWhenRoleWasCreated()
    {
        $this
            ->given($repository = $this->queryRepository(Role::class))
            ->and(
                $roleId = RoleId::next(),
                $command = new CreateRoleCommand(
                    $roleId->toNative(),
                    'A ROLE',
                    ['app']
                )
            )
            ->then()
                ->boolean($repository->isEmpty())
                    ->isTrue()
                ->and()
                ->when($this->commandBus()->dispatch($command))
                ->then()
                    ->boolean($repository->isEmpty())
                        ->isFalse()
                    ->object($repository->get($roleId))
                        ->isInstanceOf(ReadModelInterface::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testWhenPermissionWasAddedRemovedToRole()
    {
        $this
            ->given($repository = $this->queryRepository(Role::class))
            ->and($roleId = RoleId::next())
            ->and($permission = 'app.user.update')
            ->and(
                $this->commandBus()->dispatch(
                    new CreateRoleCommand(
                        $roleId->toNative(),
                        'A ROLE'
                    )
                )
            )
            ->then()
                ->object($role = $repository->get($roleId))
                    ->isInstanceOf(ReadModelInterface::class)
                ->collection($role->permissions())
                    ->isEmpty()
                ->exception(function () use ($roleId, $permission) {
                    $this->commandBus()->dispatch(
                        new AddPermissionToRoleCommand(
                            $roleId,
                            $permission
                        )
                    );
                })->isInstanceOf(NotFoundException::class)
                ->exception(function () use ($permission) {
                    $this->commandBus()->dispatch(
                        new AddPermissionToRoleCommand(
                            RoleId::next(),
                            'app.user.create'
                        )
                    );
                })->isInstanceOf(NotFoundException::class)
                ->and()
                ->when(
                    $this->commandBus()->dispatch(
                        new AddPermissionToRoleCommand(
                            $roleId,
                            'app.user.create'
                        )
                    )
                )
                ->then()
                    ->array($permissions = $repository->get($roleId)->permissions()->toArray())
                        ->hasSize(1)
                        ->contains('app.user.create')
                ->and()
                ->when(
                    $this->commandBus()->dispatch(
                        new RemovePermissionFromRoleCommand(
                            $roleId,
                            'app.user.create'
                        )
                    )
                )
                ->then()
                    ->collection($permissions = $repository->get($roleId)->permissions())
                        ->isEmpty()
        ;
    }
}
