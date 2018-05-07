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
use Behat\Service\Authentication\Mutation\LoginUserService;
use Behat\Service\SharedStorageInterface;

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
     * @var UserContext
     */
    protected $userBackgroundContext;

    /**
     * @var TokenContext
     */
    protected $tokenContext;

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * AuthenticationContext constructor.
     *
     * @param LoginUserService       $loginUserService
     * @param UserContext            $userBackgroundContext
     * @param TokenContext           $tokenContext
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        LoginUserService $loginUserService,
        UserContext $userBackgroundContext,
        TokenContext $tokenContext,
        SharedStorageInterface $sharedStorage
    ) {
        $this->loginUserService = $loginUserService;
        $this->userBackgroundContext = $userBackgroundContext;
        $this->tokenContext = $tokenContext;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given I am logged in as :email
     *
     * @param string $email
     */
    public function iAmLoggedInAs($email)
    {
        // get or create the user
        if (!$this->userBackgroundContext->hasUserWith($email)) {
            $this->userBackgroundContext->thereIsAUserIdentifiedBy($email, 'pass');
        }

        $user = $this->userBackgroundContext->getUserWith($email);
        $this->loginUser($user['userId'], $user['email'], $user['password']);
    }

    /**
     * @Given I am logged in as an administrator
     */
    public function iAmLoggedInAsAnAdministrator()
    {
        // get or create the administrator
        if (!$this->userBackgroundContext->hasAdministrator()) {
            $this->userBackgroundContext->thereIsAnAdministrator();
        }

        $user = $this->userBackgroundContext->getAdministrator();
        $this->loginUser($user['userId'], $user['email'], $user['password']);
    }

    /**
     * @param string $userId
     * @param string $email
     * @param string $password
     */
    protected function loginUser($userId, $email, $password)
    {
        $this->tokenContext->clearTokenInContext();

        $this->loginUserService->init();
        $this->loginUserService->specifyUsername($email);
        $this->loginUserService->specifyPassword($password);
        $this->loginUserService->logIn();

        $this->sharedStorage->set('userId', $userId);
    }
}
