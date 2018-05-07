<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service\Authentication\Mutation;

use Behat\Service\WebApiService;

/**
 * LogoutUserService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LogoutUserService extends WebApiService
{
    /**
     * @param string $userId
     */
    public function specifyUserId($userId)
    {
        $this->set('userId', $userId);
    }

    /**
     * Make the logout.
     */
    public function logOut()
    {
        $this->send();

        if ($this->successful()) {
            $this->sharedStorage->set('jwt', null);
        }
    }

    /**
     * @return bool
     */
    public function successful()
    {
        if ($this->hasErrors()) {
            return false;
        }

        return $this->getResponse('logoutUser') !== false;
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return 'mutation logoutUser ($userId: ID!) { 
            logoutUser(userId: $userId) 
        }';
    }
}
