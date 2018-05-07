<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Service;

use Cubiche\Domain\System\StringLiteral;
use Sandbox\Security\Domain\User\User;

/**
 * UserFactory interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface UserFactoryInterface
{
    /**
     * @param string $userId
     * @param string $username
     * @param string $password
     * @param string $email
     * @param array  $roles
     *
     * @return User
     */
    public function create($userId, $username, $password, $email, array $roles = array());

    /**
     * @param string $password
     * @param string $salt
     *
     * @return StringLiteral
     */
    public function encodePassword($password, $salt);
}
