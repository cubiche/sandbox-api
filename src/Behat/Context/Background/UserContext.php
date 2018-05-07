<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Background;

use Behat\Behat\Context\Context;
use Behat\Service\SharedStorageInterface;
use Sandbox\Security\Domain\User\Command\CreateUserCommand;
use Sandbox\Security\Domain\User\Command\DisableUserCommand;
use Sandbox\Security\Domain\User\UserId;
use Cubiche\Core\Cqrs\Command\CommandBus;

/**
 * UserContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class UserContext implements Context
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var RoleContext
     */
    protected $roleBackgroundContext;

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * UserContext constructor.
     *
     * @param CommandBus             $commandBus
     * @param RoleContext            $roleBackgroundContext
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        CommandBus $commandBus,
        RoleContext $roleBackgroundContext,
        SharedStorageInterface $sharedStorage
    ) {
        $this->commandBus = $commandBus;
        $this->roleBackgroundContext = $roleBackgroundContext;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given there is a user :email identified by :password
     *
     * @param string $email
     * @param string $password
     */
    public function thereIsAUserIdentifiedBy($email, $password)
    {
        $this->createUser($email, $email, $password, array());
    }

    /**
     * @Given there is a disabled user :email identified by :password
     *
     * @param string $email
     * @param string $password
     */
    public function thereIsADisabledUserIdentifiedBy($email, $password)
    {
        $userId = $this->createUser($email, $email, $password, array());
        $this->disableUser($userId);
    }

    /**
     * @Given /^there is a user "([^"]+)" identified by "([^"]+)" with (the role(?:|s) "[^"]+")$/
     *
     * @param string $email
     * @param string $password
     * @param array  $roles
     */
    public function thereIsAUserIdentifiedWithRole($email, $password, array $roles)
    {
        $this->createUser($email, $email, $password, $roles);
    }

    /**
     * @Given there is an administrator
     */
    public function thereIsAnAdministrator()
    {
        // get or create the admin role
        if (!$this->roleBackgroundContext->hasAdminRole()) {
            $this->roleBackgroundContext->thereIsAnAdminRole();
        }

        $this->createUser(
            'admin',
            'admin@cubiche.com',
            'plainpassword',
            array($this->roleBackgroundContext->getAdminRole())
        );
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param array  $roles
     *
     * @return string
     */
    private function createUser($username, $email, $password, array $roles = array())
    {
        $userId = UserId::next()->toNative();

        $this->commandBus->dispatch(
            new CreateUserCommand(
                $userId,
                $username,
                $password,
                $email,
                $roles,
                false
            )
        );

        $this->sharedStorage->set('user-'.$email, array(
            'userId' => $userId,
            'email' => $email,
            'password' => $password,
        ));

        return $userId;
    }

    private function disableUser($userId)
    {
        $this->commandBus->dispatch(
            new DisableUserCommand($userId)
        );
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function hasUserWith($email)
    {
        return $this->sharedStorage->has('user-'.$email);
    }

    /**
     * @return bool
     */
    public function hasAdministrator()
    {
        return $this->sharedStorage->has('user-admin@cubiche.com');
    }

    /**
     * @param string $email
     *
     * @return array
     */
    public function getUserWith($email)
    {
        return $this->sharedStorage->get('user-'.$email);
    }

    /**
     * @return array
     */
    public function getAdministrator()
    {
        return $this->sharedStorage->get('user-admin@cubiche.com');
    }
}
