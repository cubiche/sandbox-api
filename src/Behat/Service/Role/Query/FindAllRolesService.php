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
 * FindAllRolesService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllRolesService extends WebApiService
{
    /**
     * @return bool
     */
    public function successful()
    {
        if ($this->hasErrors()) {
            return false;
        }

        return $this->getResponse('roles') !== null;
    }

    /**
     * @return array|null
     */
    public function getRoles()
    {
        $this->send();

        return $this->getResponse('roles');
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            query findAllRoles () { 
                roles() {
                    id
                    name
                    permissions
                }
            }
        ';
    }
}
