<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service\Authentication\Query;

use Behat\Service\WebApiService;

/**
 * AuthenticatedUserService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AuthenticatedUserService extends WebApiService
{
    /**
     * @return bool
     */
    public function successful()
    {
        if ($this->hasErrors()) {
            return false;
        }

        return $this->getResponse('me') !== null;
    }

    /**
     * @return string|null
     */
    public function getUser()
    {
        $this->send();

        return $this->getResponse('me');
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            query {
                me {
                    id
                    username
                    email
                } 
            }
        ';
    }
}
