<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Scenario\Security;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Service\SharedStorageInterface;
use Behat\Service\User\Mutation\CreateUserService;
use Behat\Service\User\Mutation\DisableUserService;
use Behat\Service\User\Mutation\EnableUserService;
use Cubiche\Core\Validator\Assert;

/**
 * UserContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class UserContext implements Context
{
    /**
     * @var DisableUserService
     */
    protected $disableUserService;

    /**
     * @var EnableUserService
     */
    protected $enableUserService;

    /**
     * @var CreateUserService
     */
    protected $createUserService;

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * UserContext constructor.
     *
     * @param DisableUserService     $disableUserService
     * @param EnableUserService      $enableUserService
     * @param CreateUserService      $createUserService
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        DisableUserService $disableUserService,
        EnableUserService $enableUserService,
        CreateUserService $createUserService,
        SharedStorageInterface $sharedStorage
    ) {
        $this->disableUserService = $disableUserService;
        $this->enableUserService = $enableUserService;
        $this->createUserService = $createUserService;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given I want to disable a user
     */
    public function iWantToDisableAUser()
    {
        $this->disableUserService->init();
    }

    /**
     * @When I specify the user as :email to disable it
     */
    public function iSpecifyTheUserAs($email)
    {
        $userId = $this->sharedStorage->get('user-'.$email)['userId'];
        $this->disableUserService->specifyId($userId);
    }

    /**
     * @When I disable it
     */
    public function iDisableIt()
    {
        $this->disableUserService->disable();
    }

    /**
     * @Then the user will be disabled
     */
    public function theUserWillBeDisabled()
    {
        Assert::true($this->disableUserService->successful());
    }

    /**
     * @Given I want to enable a user
     */
    public function iWantToEnableAUser()
    {
        $this->enableUserService->init();
    }

    /**
     * @When I specify the user as :email to enable it
     */
    public function iSpecifyTheUserAsToEnableIt($email)
    {
        $userId = $this->sharedStorage->get('user-'.$email)['userId'];
        $this->enableUserService->specifyId($userId);
    }

    /**
     * @When I enable it
     */
    public function iEnableIt()
    {
        $this->enableUserService->enable();
    }

    /**
     * @Then the user will be enabled
     */
    public function theUserWillBeEnabled()
    {
        Assert::true($this->enableUserService->successful());
    }

    /**
     * @Given I want to create a user
     */
    public function iWantToCreateAUser()
    {
        $this->createUserService->init();
    }

    /**
     * @When I specify the username as :username
     */
    public function iSpecifyTheUsernameAs($username)
    {
        $this->createUserService->specifyUsername($username);
    }

    /**
     * @When I specify the email as :email
     */
    public function iSpecifyTheEmailAs($email)
    {
        $this->createUserService->specifyEmail($email);
    }

    /**
     * @When I specify the password as :password
     */
    public function iSpecifyThePasswordAs($password)
    {
        $this->createUserService->specifyPassword($password);
    }

    /**
     * @When I specify the roles as :roles
     */
    public function iSpecifyTheRolesAs($roles)
    {
        throw new PendingException();
    }

    /**
     * @When I create it
     */
    public function iCreateIt()
    {
        $this->createUserService->create();
    }

    /**
     * @Then the user will be created
     */
    public function theUserWillBeCreated()
    {
        Assert::true($this->createUserService->successful());
    }
}
