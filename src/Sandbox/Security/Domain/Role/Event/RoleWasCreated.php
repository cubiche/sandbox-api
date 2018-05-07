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

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Security\Domain\Role\RoleId;

/**
 * RoleWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleWasCreated extends DomainEvent
{
    /**
     * @var StringLiteral
     */
    protected $name;

    /**
     * @var StringLiteral[]
     */
    protected $permissions;

    /**
     * RoleWasCreated constructor.
     *
     * @param RoleId        $roleId
     * @param StringLiteral $name
     * @param array         $permissions
     */
    public function __construct(
        RoleId $roleId,
        StringLiteral $name,
        array $permissions = array()
    ) {
        parent::__construct($roleId);

        $this->name = $name;
        $this->permissions = $permissions;
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
    public function name()
    {
        return $this->name;
    }

    /**
     * @return StringLiteral[]
     */
    public function permissions()
    {
        return $this->permissions;
    }
}
