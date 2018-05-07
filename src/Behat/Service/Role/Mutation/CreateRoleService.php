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
 * CreateRoleService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateRoleService extends WebApiService
{
    /**
     * {@inheritdoc}
     */
    protected function defaults()
    {
        $this->set('permissions', array());
        $this->set('name', '');
    }

    /**
     * @param string $name
     */
    public function specifyName($name)
    {
        $this->set('name', $name);
    }

    /**
     * @param array $permissions
     */
    public function specifyPermissions(array $permissions)
    {
        $this->set('permissions', $permissions);
    }

    /**
     * Create the role.
     */
    public function create()
    {
        $this->send();
    }

    /**
     * @return bool
     */
    public function successful()
    {
        return $this->roleId() !== null;
    }

    /**
     * @return string|null
     */
    public function roleId()
    {
        if ($this->hasErrors()) {
            return null;
        }

        return $this->getResponse('createRole')['id'];
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            mutation createRole (
                $name: String!, 
                $permissions: [String]
            ) { 
                createRole(
                    name: $name, 
                    permissions: $permissions
                ) {
                    id
                }
            }
        ';
    }
}
