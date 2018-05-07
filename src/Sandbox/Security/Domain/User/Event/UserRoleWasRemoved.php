<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Event;

use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\User\UserId;
use Cubiche\Domain\EventSourcing\DomainEvent;

/**
 * UserRoleWasRemoved class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserRoleWasRemoved extends DomainEvent
{
    /**
     * @var RoleId
     */
    protected $roleId;

    /**
     * UserRoleWasRemoved constructor.
     *
     * @param UserId $userId
     * @param RoleId $roleId
     */
    public function __construct(UserId $userId, RoleId $roleId)
    {
        parent::__construct($userId);

        $this->roleId = $roleId;
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->aggregateId();
    }

    /**
     * @return RoleId
     */
    public function roleId()
    {
        return $this->roleId;
    }
}
