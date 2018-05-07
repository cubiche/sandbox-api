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
 * LoginUserService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LoginUserService extends WebApiService
{
    /**
     * @param string $username
     */
    public function specifyUsername($username)
    {
        $this->set('usernameOrEmail', $username);
    }

    /**
     * @param string $email
     */
    public function specifyEmail($email)
    {
        $this->set('usernameOrEmail', $email);
    }

    /**
     * @param string $password
     */
    public function specifyPassword($password)
    {
        $this->set('password', $password);
    }

    /**
     * Make the login.
     */
    public function logIn()
    {
        $this->send();

        $this->sharedStorage->set('jwt', $this->getJWT());
    }

    /**
     * @return bool
     */
    public function successful()
    {
        if ($this->hasErrors()) {
            return false;
        }

        return $this->getResponse('loginUser') !== null;
    }

    /**
     * @return string|null
     */
    public function getJWT()
    {
        return $this->getResponse('loginUser');
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return 'mutation loginUser ($usernameOrEmail: String!, $password: String!) { 
            loginUser(usernameOrEmail: $usernameOrEmail, password: $password) 
        }';
    }
}
