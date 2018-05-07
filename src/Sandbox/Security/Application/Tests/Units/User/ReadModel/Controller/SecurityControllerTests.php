<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Tests\Units\User\ReadModel\Controller;

use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Application\Tests\Units\TestCase;
use Sandbox\Security\Application\Tests\Units\User\SettingSecurityContextTrait;
use Sandbox\Security\Application\User\ReadModel\Controller\SecurityController;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Domain\User\UserId;

/**
 * SecurityControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SecurityControllerTests extends TestCase
{
    use SettingSecurityContextTrait;

    /**
     * @return User
     */
    protected function createUser()
    {
        $token = $this->getTokenContext()->getToken();
        $userRepository = $this->queryRepository(User::class);

        $user = new User(
            UserId::fromNative($token->userId()),
            StringLiteral::fromNative($this->faker->userName),
            EmailAddress::fromNative($token->email()),
            true,
            true
        );

        $userRepository->persist($user);

        return $user;
    }

    /**
     * @return SecurityController
     */
    protected function controller()
    {
        return new SecurityController($this->queryBus(), $this->getSecurityContext());
    }

    /**
     * @return SecurityController
     */
    protected function controllerWithEmptyToken()
    {
        return new SecurityController($this->queryBus(), $this->getEmptySecurityContext());
    }

    /**
     * Test FindAuthenticatedUserAction method.
     */
    public function testFindAuthenticatedUserAction()
    {
        $this
            ->given($user = $this->createUser())
            ->then()
                ->object($userFound = $this->controller()->findAuthenticatedUserAction())
                    ->isInstanceOf(User::class)
                ->string($userFound->id()->toNative())
                    ->isEqualTo($user->id()->toNative())
                ->and()
                ->then()
                    ->exception(function () {
                        $this->controllerWithEmptyToken()->findAuthenticatedUserAction();
                    })->isInstanceOf(NotFoundException::class)
        ;
    }
}
