<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service\Role\Query;

use Behat\Service\WebApiService;

/**
 * FindOneRoleService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneRoleService extends WebApiService
{
    /**
     * @param string $roleId
     */
    public function specifyRoleId($roleId)
    {
        $this->set('roleId', $roleId);
    }

    /**
     * @return bool
     */
    public function successful()
    {
        if ($this->hasErrors()) {
            return false;
        }

        return $this->getResponse('role') !== null;
    }

    /**
     * @return array|null
     */
    public function getRole()
    {
        $this->send();

        return $this->getResponse('role');
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            query findOneRole (
                $roleId: ID!
            ) { 
                role(
                    roleId: $roleId
                ) {
                    id
                    name
                    permissions
                }
            }
        ';
    }
}
