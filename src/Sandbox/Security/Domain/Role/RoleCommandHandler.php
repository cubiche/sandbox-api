<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\Command\AddPermissionToRoleCommand;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\Command\RemovePermissionFromRoleCommand;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * RoleCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var QueryRepositoryInterface
     */
    protected $permissionRepository;

    /**
     * RoleCommandHandler constructor.
     *
     * @param RepositoryInterface      $repository
     * @param QueryRepositoryInterface $permissionRepository
     */
    public function __construct(RepositoryInterface $repository, QueryRepositoryInterface $permissionRepository)
    {
        $this->repository = $repository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param CreateRoleCommand $command
     */
    public function createRole(CreateRoleCommand $command)
    {
        $permissions = array();
        foreach ($command->permissions() as $permission) {
            $permissions[] = $this->findPermissionOr404($permission);
        }

        $role = new Role(
            RoleId::fromNative($command->roleId()),
            StringLiteral::fromNative($command->name()),
            $permissions
        );

        $this->repository->persist($role);
    }

    /**
     * @param AddPermissionToRoleCommand $command
     */
    public function addPermissionToRole(AddPermissionToRoleCommand $command)
    {
        $role = $this->findOr404($command->roleId());
        $role->addPermission($this->findPermissionOr404($command->permission()));

        $this->repository->persist($role);
    }

    /**
     * @param RemovePermissionFromRoleCommand $command
     */
    public function removePermissionFromRole(RemovePermissionFromRoleCommand $command)
    {
        $role = $this->findOr404($command->roleId());
        $role->removePermission($this->findPermissionOr404($command->permission()));

        $this->repository->persist($role);
    }

    /**
     * @param string $roleId
     *
     * @return Role
     */
    protected function findOr404($roleId)
    {
        /** @var Role $role */
        $role = $this->repository->get(RoleId::fromNative($roleId));
        if ($role === null) {
            throw new NotFoundException(sprintf(
                'There is no role with id: %s',
                $roleId
            ));
        }

        return $role;
    }

    /**
     * @param string $permission
     *
     * @return StringLiteral
     */
    protected function findPermissionOr404($permission)
    {
        if ($this->permissionRepository->findOne(Criteria::this()->eq($permission)) === null) {
            throw new NotFoundException(sprintf(
                'There is no permission with name: %s',
                $permission
            ));
        }

        return StringLiteral::fromNative($permission);
    }
}
