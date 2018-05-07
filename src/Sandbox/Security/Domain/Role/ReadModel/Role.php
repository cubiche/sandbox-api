<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role\ReadModel;

use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Security\Domain\Role\RoleId;

/**
 * Role class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Role extends Entity implements ReadModelInterface
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
     */
    public function __construct(RoleId $roleId, StringLiteral $name)
    {
        parent::__construct($roleId);

        $this->name = $name;
        $this->permissions = new ArraySet();
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
     * @param StringLiteral $name
     */
    public function setName(StringLiteral $name)
    {
        $this->name = $name;
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
        $this->permissions->add($permission);
    }

    /**
     * @param StringLiteral $permission
     */
    public function removePermission(StringLiteral $permission)
    {
        $this->permissions->remove($permission);
    }
}
