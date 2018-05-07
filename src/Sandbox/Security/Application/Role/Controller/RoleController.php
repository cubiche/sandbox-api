<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Role\Controller;

use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\Security\Domain\Role\Command\AddPermissionToRoleCommand;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\Command\RemovePermissionFromRoleCommand;
use Sandbox\Security\Domain\Role\RoleId;

/**
 * RoleController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleController extends CommandController
{
    /**
     * @param string $name
     * @param array  $permissions
     *
     * @return string
     */
    public function createAction($name, array $permissions = array())
    {
        $roleId = RoleId::next()->toNative();

        $this->commandBus()->dispatch(
            new CreateRoleCommand($roleId, $name, $permissions)
        );

        return $roleId;
    }

    /**
     * @param string $roleId
     * @param string $permission
     *
     * @return bool
     */
    public function addPermissionAction($roleId, $permission)
    {
        $this->commandBus()->dispatch(
            new AddPermissionToRoleCommand($roleId, $permission)
        );

        return true;
    }

    /**
     * @param string $roleId
     * @param string $permission
     *
     * @return bool
     */
    public function removePermissionAction($roleId, $permission)
    {
        $this->commandBus()->dispatch(
            new RemovePermissionFromRoleCommand($roleId, $permission)
        );

        return true;
    }
}
