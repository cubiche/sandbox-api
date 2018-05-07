<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service\Role\Mutation;

use Behat\Service\WebApiService;

/**
 * AddPermissionsToRoleService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AddPermissionsToRoleService extends WebApiService
{
    /**
     * {@inheritdoc}
     */
    protected function defaults()
    {
        $this->set('permissions', array());
    }

    /**
     * @param string $roleId
     */
    public function specifyRoleId($roleId)
    {
        $this->set('roleId', $roleId);
    }

    /**
     * @param array $permissions
     */
    public function specifyPermissions(array $permissions)
    {
        $this->set('permissions', $permissions);
    }

    /**
     * Add its.
     */
    public function addIt()
    {
        $this->send();
    }

    /**
     * @return bool
     */
    public function successful()
    {
        return $this->role() !== null;
    }

    /**
     * @return array|null
     */
    public function role()
    {
        if ($this->hasErrors()) {
            return null;
        }

        return $this->getResponse('addPermissionsToRole');
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            mutation addPermissionsToRole (
                $roleId: ID!, 
                $permissions: [String]!
            ) { 
                addPermissionsToRole(
                    roleId: $roleId, 
                    permissions: $permissions
                ) {
                    id
                    name
                    permissions
                }
            }
        ';
    }
}
