<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role\ReadModel;

use Cubiche\Domain\System\StringLiteral;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindAllRoles;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleById;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleByName;
use Sandbox\Security\Domain\Role\ReadModel\Role;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * RoleQueryHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleQueryHandlerTests extends TestCase
{
    /**
     * @param string $roleId
     * @param string $roleName
     */
    protected function addRoleToRepository($roleId, $roleName)
    {
        $repository = $this->queryRepository(Role::class);

        $repository->persist(
            new Role(
                $roleId,
                StringLiteral::fromNative($roleName)
            )
        );
    }

    /**
     * @param string $roleId
     *
     * @return Role|null
     */
    protected function findOneRoleById($roleId)
    {
        return $this->queryBus()->dispatch(new FindOneRoleById($roleId));
    }

    /**
     * @param string $roleName
     *
     * @return Role|null
     */
    protected function findOneRoleByName($roleName)
    {
        return $this->queryBus()->dispatch(new FindOneRoleByName($roleName));
    }

    /**
     * @return Role[]
     */
    protected function findAllRoles()
    {
        return $this->queryBus()->dispatch(new FindAllRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function testFindOneRoleById()
    {
        $this
            ->given($roleId = RoleId::next())
            ->then()
                ->variable($this->findOneRoleById($roleId->toNative()))
                    ->isNull()
            ->and()
            ->when($this->addRoleToRepository($roleId, 'ROLE_NAME'))
            ->then()
                ->object($role = $this->findOneRoleById($roleId->toNative()))
                    ->isInstanceOf(Role::class)
                ->object($role->roleId())
                    ->isEqualTo($roleId)
                ->variable($this->findOneRoleById(RoleId::nextUUIDValue()))
                    ->isNull()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testFindAllRoles()
    {
        $this
            ->given($roles = $this->findAllRoles())
            ->then()
                ->array(iterator_to_array($roles))
                    ->isEmpty()
                ->and()
                ->when($this->addRoleToRepository(RoleId::next(), 'ROLE_NAME'))
                ->and($this->addRoleToRepository(RoleId::next(), 'ANOTHER_NAME'))
                ->and($this->addRoleToRepository(RoleId::next(), 'THIRD_NAME'))
                ->then()
                    ->array(iterator_to_array($this->findAllRoles()))
                        ->isNotEmpty()
                        ->hasSize(3)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testFindOneRoleByName()
    {
        $this
            ->given($roleName = 'ROLE_NAME')
            ->then()
                ->variable($this->findOneRoleByName($roleName))
                    ->isNull()
                ->and()
                ->when($this->addRoleToRepository(RoleId::next(), $roleName))
                ->then()
                    ->object($role = $this->findOneRoleByName($roleName))
                        ->isInstanceOf(Role::class)
                    ->object($role->name())
                        ->isEqualTo($roleName)
                    ->variable($this->findOneRoleByName('ROLE_NO_EXIST'))
                        ->isNull()
        ;
    }
}
