<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role\Event;

use Sandbox\Security\Domain\Role\RoleId;
use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\System\StringLiteral;

/**
 * PermissionWasAddedToRole class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PermissionWasAddedToRole extends DomainEvent
{
    /**
     * @var StringLiteral
     */
    protected $permission;

    /**
     * PermissionWasAddedToRole constructor.
     *
     * @param RoleId        $roleId
     * @param StringLiteral $permission
     */
    public function __construct(RoleId $roleId, StringLiteral $permission)
    {
        parent::__construct($roleId);

        $this->permission = $permission;
    }

    /**
     * @return RoleId
     */
    public function roleId()
    {
        return $this->aggregateId();
    }

    /**
     * @return StringLiteral
     */
    public function permission()
    {
        return $this->permission;
    }
}
