<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Role\ReadModel\Controller;

use Cubiche\Core\Collections\CollectionInterface;
use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindAllRoles;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleById;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleByName;
use Sandbox\Security\Domain\Role\ReadModel\Role;

/**
 * RoleController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleController extends QueryController
{
    /**
     * @return CollectionInterface|Role[]
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(new FindAllRoles());
    }

    /**
     * @param string $roleId
     *
     * @return Role
     */
    public function findOneByIdAction($roleId)
    {
        return $this->queryBus()->dispatch(new FindOneRoleById($roleId));
    }

    /**
     * @param string $name
     *
     * @return Role
     */
    public function findOneByNameAction($name)
    {
        return $this->queryBus()->dispatch(new FindOneRoleByName($name));
    }
}
