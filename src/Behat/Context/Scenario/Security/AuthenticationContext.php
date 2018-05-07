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
use Behat\Service\Authentication\Mutation\LoginUserService;
use Behat\Service\Authentication\Mutation\LogoutUserService;
use Behat\Service\Authentication\Query\AuthenticatedUserService;
use Behat\Service\SharedStorageInterface;
use Cubiche\Core\Validator\Assert;

/**
 * AuthenticationContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class AuthenticationContext implements Context
{
    /**
     * @var LoginUserService
     */
    protected $loginUserService;

    /**
     * @var LogoutUserService
     */
    protected $logoutUserService;

    /**
     * @var AuthenticatedUserService
     */
    protected $authenticatedUserService;

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * AuthenticationContext constructor.
     *
     * @param LoginUserService         $loginUserService
     * @param LogoutUserService        $logoutUserService
     * @param AuthenticatedUserService $authenticatedUserService
     * @param SharedStorageInterface   $sharedStorage
     */
    public function __construct(
        LoginUserService $loginUserService,
        LogoutUserService $logoutUserService,
        AuthenticatedUserService $authenticatedUserService,
        SharedStorageInterface $sharedStorage
    ) {
        $this->loginUserService = $loginUserService;
        $this->logoutUserService = $logoutUserService;
        $this->authenticatedUserService = $authenticatedUserService;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given I want to log in
     */
    public function iWantToLogIn()
    {
        $this->loginUserService->init();
    }

    /**
     * @When I specify the username as :username
     *
     * @param string $username
     */
    public function iSpecifyTheUsername($username)
    {
        $this->loginUserService->specifyUsername($username);
    }

    /**
     * @When I specify the password as :password
     * @When I do not specify the password
     *
     * @param string $password
     */
    public function iSpecifyThePassword($password)
    {
        $this->loginUserService->specifyPassword($password);
    }

    /**
     * @When I log in
     */
    public function iLogIn()
    {
        $this->loginUserService->logIn();
    }

    /**
     * @Then I should be logged in
     */
    public function iShouldBeLoggedIn()
    {
        Assert::true($this->loginUserService->successful());
        Assert::notNull($this->authenticatedUserService->getUser());
    }

    /**
     * @Then I should not be logged in
     */
    public function iShouldNotBeLoggedIn()
    {
        Assert::false($this->loginUserService->successful());
        Assert::null($this->authenticatedUserService->getUser());
    }

    /**
     * @Then I should be notified that :username doesn't exist
     */
    public function iShouldBeNotifiedThatUserDoesntExist()
    {
        // 404 User not found
        Assert::true($this->loginUserService->hasErrorWithCode(404));
    }

    /**
     * @Then I should be notified about bad credentials
     */
    public function iShouldBeNotifiedAboutBadCredentials()
    {
        // 403 Authentication exception
        Assert::true($this->loginUserService->hasErrorWithCode(403));
    }

    /**
     * @Given I want to log out
     */
    public function iWantToLogOut()
    {
        $userId = $this->sharedStorage->get('userId');
        Assert::notNull($userId);

        $this->logoutUserService->init();
        $this->logoutUserService->specifyUserId($userId);
    }

    /**
     * @When I log out
     */
    public function iLogOut()
    {
        $this->logoutUserService->logOut();
    }

    /**
     * @Then I should be logged out
     */
    public function iShouldBeLoggedOut()
    {
        Assert::true($this->logoutUserService->successful());
    }
}
