<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service\User\Mutation;

use Behat\Service\WebApiService;

/**
 * CreateUserService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateUserService extends WebApiService
{
    /**
     * {@inheritdoc}
     */
    protected function defaults()
    {
        $this->set('roles', array());
        $this->set('verificationByEmail', false);
    }

    /**
     * @param string $username
     */
    public function specifyUsername($username)
    {
        $this->set('username', $username);
    }

    /**
     * @param string $email
     */
    public function specifyEmail($email)
    {
        $this->set('email', $email);
    }

    /**
     * @param string $password
     */
    public function specifyPassword($password)
    {
        $this->set('password', $password);
    }

    /**
     * @param array $roles
     */
    public function specifyRoles(array $roles)
    {
        $this->set('roles', $roles);
    }

    /**
     * @param bool $verificationByEmail
     */
    public function specifyVerificationByEmail($verificationByEmail)
    {
        $this->set('verificationByEmail', $verificationByEmail);
    }

    /**
     * Create the user.
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
        return $this->userId() !== null;
    }

    /**
     * @return string|null
     */
    public function userId()
    {
        if ($this->hasErrors()) {
            return null;
        }

        return $this->getResponse('createUser')['id'];
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            mutation createUser (
                $username: String!, 
                $email: String!, 
                $password: String!, 
                $roles: [ID],
                $verificationByEmail: Boolean!
            ) { 
                createUser(
                    username: $username, 
                    email: $email, 
                    password: $password, 
                    roles: $roles,
                    verificationByEmail: $verificationByEmail
                ) {
                    id
                }
            }
        ';
    }
}
