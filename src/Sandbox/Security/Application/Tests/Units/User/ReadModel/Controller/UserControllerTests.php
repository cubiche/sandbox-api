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

use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Core\Application\Tests\Units\SettingTokenContextTrait;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Application\Tests\Units\TestCase;
use Sandbox\Security\Application\User\ReadModel\Controller\UserController;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserControllerTests extends TestCase
{
    use SettingTokenContextTrait;

    /**
     * @return QueryRepositoryInterface
     */
    protected function repository()
    {
        return $this->queryRepository(User::class);
    }

    /**
     * @return UserController
     */
    public function controller()
    {
        return new UserController($this->queryBus(), $this->getTokenContext());
    }

    /**
     * @return User
     */
    protected function createUser()
    {
        return new User(
            UserId::next(),
            StringLiteral::fromNative($this->faker->name),
            EmailAddress::fromNative($this->faker->email),
            true,
            true
        );
    }

    /**
     * Test FindAllAction method.
     */
    public function testFindAllAction()
    {
        $this
            ->then()
                ->array(iterator_to_array($this->controller()->findAllAction()))
                    ->isEmpty()
                ->and()
                ->when($this->repository()->persist($this->createUser()))
                ->and($this->repository()->persist($this->createUser()))
                ->then()
                    ->array(iterator_to_array($this->controller()->findAllAction()))
                        ->isNotEmpty()
                            ->hasSize(2)
        ;
    }

    /**
     * Test FindOneByPasswordResetTokenAction method.
     */
    public function testFindOneByPasswordResetTokenAction()
    {
        /* @var User $user */
        $this
            ->given($user = $this->createUser())
            ->and($user->setPasswordResetToken(StringLiteral::fromNative('abc123')))
            ->when($this->repository()->persist($user))
            ->then()
                ->object(
                    $userFound = $this->controller()->findOneByPasswordResetTokenAction(
                        $user->passwordResetToken()->toNative()
                    )
                )->isInstanceOf(User::class)
                ->string($userFound->userId()->toNative())
                    ->isEqualTo($user->userId()->toNative())
                ->and()
                ->when($user = $this->createUser())
                ->and($user->setPasswordResetToken(StringLiteral::fromNative('123abc')))
                ->then()
                    ->exception(function () use ($user) {
                        $this->controller()->findOneByPasswordResetTokenAction($user->passwordResetToken()->toNative());
                    })->isInstanceOf(NotFoundException::class)
        ;
    }

    /**
     * Test FindOneByIdAction method.
     */
    public function testFindOneByIdAction()
    {
        /* @var User $user */
        $this
            ->given($user = $this->createUser())
            ->when($this->repository()->persist($user))
            ->then()
                ->object(
                    $userFound = $this->controller()->findOneByIdAction(
                        $user->id()->toNative()
                    )
                )
                    ->isInstanceOf(User::class)
                ->string($userFound->userId()->toNative())
                    ->isEqualTo($user->userId()->toNative())
                ->and()
                ->then()
                    ->exception(function () {
                        $this->controller()->findOneByIdAction(UserId::nextUUIDValue());
                    })->isInstanceOf(NotFoundException::class)
        ;
    }
}
