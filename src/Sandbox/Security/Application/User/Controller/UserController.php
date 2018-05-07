<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\User\Controller;

use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\Security\Domain\User\Command\AddUserRoleCommand;
use Sandbox\Security\Domain\User\Command\CreateUserCommand;
use Sandbox\Security\Domain\User\Command\DisableUserCommand;
use Sandbox\Security\Domain\User\Command\EnableUserCommand;
use Sandbox\Security\Domain\User\Command\RemoveUserRoleCommand;
use Sandbox\Security\Domain\User\Command\ResetUserPasswordCommand;
use Sandbox\Security\Domain\User\Command\ResetUserPasswordRequestCommand;
use Sandbox\Security\Domain\User\Command\VerifyUserCommand;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserController extends CommandController
{
    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param array  $roles
     * @param bool   $verificationByEmail
     *
     * @return string
     */
    public function createAction(
        $username,
        $password,
        $email,
        array $roles = array(),
        $verificationByEmail = true
    ) {
        $userId = UserId::next()->toNative();

        $this->commandBus()->dispatch(
            new CreateUserCommand($userId, $username, $password, $email, $roles, $verificationByEmail)
        );

        return $userId;
    }

    /**
     * @param string $userId
     * @param string $roleId
     *
     * @return bool
     */
    public function addRoleAction($userId, $roleId)
    {
        $this->commandBus()->dispatch(
            new AddUserRoleCommand($userId, $roleId)
        );

        return true;
    }

    /**
     * @param string $userId
     * @param string $roleId
     *
     * @return bool
     */
    public function removeRoleAction($userId, $roleId)
    {
        $this->commandBus()->dispatch(
            new RemoveUserRoleCommand($userId, $roleId)
        );

        return true;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function resetPasswordRequestAction($email)
    {
        $this->commandBus()->dispatch(
            new ResetUserPasswordRequestCommand($email)
        );

        return true;
    }

    /**
     * @param string $userId
     * @param string $password
     *
     * @return bool
     */
    public function resetPasswordAction($userId, $password)
    {
        $this->commandBus()->dispatch(
            new ResetUserPasswordCommand($userId, $password)
        );

        return true;
    }

    /**
     * @param string $emailVerificationToken
     *
     * @return bool
     */
    public function verifyAction($emailVerificationToken)
    {
        $this->commandBus()->dispatch(
            new VerifyUserCommand($emailVerificationToken)
        );

        return true;
    }

    /**
     * @param string $userId
     *
     * @return bool
     */
    public function disableAction($userId)
    {
        $this->commandBus()->dispatch(
            new DisableUserCommand($userId)
        );

        return true;
    }

    /**
     * @param string $userId
     *
     * @return bool
     */
    public function enableAction($userId)
    {
        $this->commandBus()->dispatch(
            new EnableUserCommand($userId)
        );

        return true;
    }
}
