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

use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Security\Domain\Role\Event\PermissionWasAddedToRole;
use Sandbox\Security\Domain\Role\Event\PermissionWasRemovedFromRole;
use Sandbox\Security\Domain\Role\Event\RoleWasCreated;

/**
 * Role class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Role extends AggregateRoot
{
    /**
     * @var StringLiteral
     */
    protected $name;

    /**
     * @var ArraySet
     */
    protected $permissions;

    /**
     * Role constructor.
     *
     * @param RoleId        $roleId
     * @param StringLiteral $name
     * @param array         $permissions
     */
    public function __construct(RoleId $roleId, StringLiteral $name, array $permissions = array())
    {
        parent::__construct($roleId);

        $this->recordAndApplyEvent(
            new RoleWasCreated($roleId, $name, $permissions)
        );
    }

    /**
     * @return RoleId
     */
    public function roleId()
    {
        return $this->id;
    }

    /**
     * @return StringLiteral
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return ArraySet
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * @param StringLiteral $permission
     */
    public function addPermission(StringLiteral $permission)
    {
        $this->recordAndApplyEvent(
            new PermissionWasAddedToRole($this->roleId(), $permission)
        );
    }

    /**
     * @param StringLiteral $permission
     */
    public function removePermission(StringLiteral $permission)
    {
        $this->recordAndApplyEvent(
            new PermissionWasRemovedFromRole($this->roleId(), $permission)
        );
    }

    /**
     * @param RoleWasCreated $event
     */
    public function applyRoleWasCreated(RoleWasCreated $event)
    {
        $this->name = $event->name();
        $this->permissions = new ArraySet();

        foreach ($event->permissions() as $permission) {
            $this->permissions->add($permission);
        }
    }

    /**
     * @param PermissionWasAddedToRole $event
     */
    public function applyPermissionWasAddedToRole(PermissionWasAddedToRole $event)
    {
        $this->permissions->add($event->permission());
    }

    /**
     * @param PermissionWasRemovedFromRole $event
     */
    public function applyPermissionWasRemovedFromRole(PermissionWasRemovedFromRole $event)
    {
        $this->permissions->remove($event->permission());
    }
}
