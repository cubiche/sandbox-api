<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Tests\Units\User\ProcessManager;

use Sandbox\Security\Application\Tests\Units\TestCase;
use Sandbox\Security\Application\User\ProcessManager\UserRegisterProcessManager;

/**
 * UserRegisterProcessManagerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserRegisterProcessManagerTests extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function testCreate()
    {
        $this
            ->given(
                $processManager = new UserRegisterProcessManager(
                    $this->commandBus(),
                    $this->permissionRepository()
                )
            )
            ->then()
                ->array($processManager->getSubscribedEvents())
                    ->isNotEmpty()
        ;
    }

    /**
     * Test WhenUserWasCreated method.
     */
    public function testWhenUserWasCreated()
    {
        // todo: Implement testWhenUserWasCreated().
    }

    /**
     * Test WhenUserVerificationWasRequested method.
     */
    public function testWhenUserVerificationWasRequested()
    {
        // todo: Implement testWhenUserVerificationWasRequested().
    }

    /**
     * Test WhenUserWasVerified method.
     */
    public function testWhenUserWasVerified()
    {
        // todo: Implement testWhenUserWasVerified().
    }

    /**
     * Test GetSubscribedEvents method.
     */
    public function testGetSubscribedEvents()
    {
        // todo: Implement testGetSubscribedEvents().
    }
}
