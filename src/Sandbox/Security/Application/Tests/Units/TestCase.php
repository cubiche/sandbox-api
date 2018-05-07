<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Tests\Units;

use Sandbox\Security\Application\Tests\Units\User\SettingSecurityContextTrait;
use Sandbox\Security\Application\User\Command\GenerateJWTCommand;
use Sandbox\Security\Application\User\ProcessManager\SecurityProcessManager;
use Sandbox\Security\Application\User\SecurityCommandHandler;
use Sandbox\Security\Domain\Tests\Units\TestCase as BaseTestCase;
use Sandbox\Security\Domain\User\ReadModel\User;

/**
 * TestCase class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    use SettingSecurityContextTrait;

    /**
     * @return array
     */
    protected function commandHandlers()
    {
        $securityCommandHandler = new SecurityCommandHandler(
            $this->queryRepository(User::class),
            $this->getTokenContext(),
            $this->getTokenManager()
        );

        return array_merge(parent::commandHandlers(), [
            GenerateJWTCommand::class => $securityCommandHandler,
        ]);
    }

    /**
     * @return array
     */
    protected function eventSubscribers()
    {
        $securityProcessManager = new SecurityProcessManager($this->commandBus());

        return array_merge(parent::eventSubscribers(), [
            $securityProcessManager,
        ]);
    }
}
