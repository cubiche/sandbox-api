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

use Cubiche\Core\Cqrs\Command\CommandBus;
use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\Security\Domain\User\Command\LoginUserCommand;
use Sandbox\Security\Domain\User\Command\LogoutUserCommand;
use Sandbox\Security\Domain\User\Service\SecurityContextInterface;

/**
 * SecurityController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SecurityController extends CommandController
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * SecurityController constructor.
     *
     * @param CommandBus               $commandBus
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(
        CommandBus $commandBus,
        SecurityContextInterface $securityContext
    ) {
        parent::__construct($commandBus);

        $this->securityContext = $securityContext;
    }

    /**
     * @param string $usernameOrEmail
     * @param string $password
     *
     * @return bool
     */
    public function loginAction($usernameOrEmail, $password)
    {
        if ($this->securityContext->isAuthenticated()) {
            return $this->securityContext->getJWT();
        }

        $this->commandBus()->dispatch(
            new LoginUserCommand($usernameOrEmail, $password)
        );

        return $this->securityContext->getJWT();
    }

    /**
     * @param string $userId
     *
     * @return bool
     */
    public function logoutAction($userId)
    {
        if (!$this->securityContext->isAuthenticated()) {
            return false;
        }

        $this->commandBus()->dispatch(
            new LogoutUserCommand($userId)
        );

        return true;
    }
}
