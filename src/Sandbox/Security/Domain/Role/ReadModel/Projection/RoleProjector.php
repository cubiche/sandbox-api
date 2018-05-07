<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role\ReadModel\Projection;

use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\Event\PermissionWasAddedToRole;
use Sandbox\Security\Domain\Role\Event\PermissionWasRemovedFromRole;
use Sandbox\Security\Domain\Role\Event\RoleWasCreated;
use Sandbox\Security\Domain\Role\ReadModel\Role;
use Sandbox\Security\Domain\Role\RoleId;

/**
 * RoleProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * @var QueryRepositoryInterface
     */
    protected $permissionRepository;

    /**
     * RoleProjector constructor.
     *
     * @param QueryRepositoryInterface $repository
     * @param QueryRepositoryInterface $permissionRepository
     */
    public function __construct(QueryRepositoryInterface $repository, QueryRepositoryInterface $permissionRepository)
    {
        $this->repository = $repository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param RoleWasCreated $event
     */
    public function whenRoleWasCreated(RoleWasCreated $event)
    {
        $readModel = new Role(
            $event->roleId(),
            $event->name()
        );

        foreach ($event->permissions() as $permission) {
            $readModel->addPermission($this->findPermissionOr404($permission->toNative()));
        }

        $this->repository->persist($readModel);
    }

    /**
     * @param PermissionWasAddedToRole $event
     */
    public function whenPermissionWasAddedToRole(PermissionWasAddedToRole $event)
    {
        $readModel = $this->findOr404($event->roleId());
        $readModel->addPermission($this->findPermissionOr404($event->permission()->toNative()));

        $this->repository->persist($readModel);
    }

    /**
     * @param PermissionWasRemovedFromRole $event
     */
    public function whenPermissionWasRemovedFromRole(PermissionWasRemovedFromRole $event)
    {
        $readModel = $this->findOr404($event->roleId());
        $readModel->removePermission($this->findPermissionOr404($event->permission()->toNative()));

        $this->repository->persist($readModel);
    }

    /**
     * @param RoleId $roleId
     *
     * @return Role
     */
    private function findOr404(RoleId $roleId)
    {
        /** @var Role $role */
        $role = $this->repository->get($roleId);
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

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            RoleWasCreated::class => ['whenRoleWasCreated', 250],
            PermissionWasAddedToRole::class => ['whenPermissionWasAddedToRole', 250],
            PermissionWasRemovedFromRole::class => ['whenPermissionWasRemovedFromRole', 250],
        ];
    }
}
