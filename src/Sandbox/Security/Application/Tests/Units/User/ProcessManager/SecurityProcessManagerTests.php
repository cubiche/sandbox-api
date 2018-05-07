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

use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\DateTime\DateTime;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Security\Application\Tests\Units\TestCase;
use Sandbox\Security\Application\Tests\Units\User\SettingSecurityContextTrait;
use Sandbox\Security\Application\User\ProcessManager\SecurityProcessManager;
use Sandbox\Security\Domain\User\Event\UserHasLoggedIn;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Domain\User\UserId;

/**
 * SecurityProcessManagerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SecurityProcessManagerTests extends TestCase
{
    use SettingSecurityContextTrait;

    /**
     * @return QueryRepositoryInterface
     */
    protected function repository()
    {
        return $this->queryRepository(User::class);
    }

    /**
     * @return User
     */
    protected function addUser()
    {
        $user = new User(
            UserId::next(),
            StringLiteral::fromNative('johnsnow'),
            EmailAddress::fromNative('johnsnow@gameofthrones.com'),
            true,
            true
        );

        $this->repository()->persist($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function testCreate()
    {
        $this
            ->given(
                $processManager = new SecurityProcessManager(
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
     * Test WhenUserHasLoggedIn method.
     */
    public function testWhenUserHasLoggedIn()
    {
        /* @var User $user */
        $this
            ->given($user = $this->addUser())
            ->when($jwt = $this->getTokenContext()->getJWT())
            ->then()
                ->string($jwt)
                    ->isNotEmpty()
                ->string($this->getTokenContext()->getToken()->userId())
                    ->isNotEqualTo($user->userId()->toNative())
                ->and()
                ->when($this->eventBus()->dispatch(
                    new UserHasLoggedIn(
                        $user->userId(),
                        DateTime::now()
                    )
                ))
                ->then()
                    ->string($this->getTokenContext()->getJWT())
                        ->isNotEqualTo($jwt)
                    ->string($this->getTokenContext()->getToken()->userId())
                        ->isEqualTo($user->userId()->toNative())
        ;
    }
}
