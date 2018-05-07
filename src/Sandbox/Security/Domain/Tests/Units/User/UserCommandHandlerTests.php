<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User;

use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Core\Domain\Exception\AuthenticationException;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\Command\AddUserRoleCommand;
use Sandbox\Security\Domain\User\Command\CreateUserCommand;
use Sandbox\Security\Domain\User\Command\DisableUserCommand;
use Sandbox\Security\Domain\User\Command\EnableUserCommand;
use Sandbox\Security\Domain\User\Command\LoginUserCommand;
use Sandbox\Security\Domain\User\Command\LogoutUserCommand;
use Sandbox\Security\Domain\User\Command\RemoveUserRoleCommand;
use Sandbox\Security\Domain\User\Command\ResetUserPasswordCommand;
use Sandbox\Security\Domain\User\Command\ResetUserPasswordRequestCommand;
use Sandbox\Security\Domain\User\Command\VerifyUserCommand;
use Sandbox\Security\Domain\User\User;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserCommandHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserCommandHandlerTests extends TestCase
{
    /**
     * @return RepositoryInterface
     */
    protected function repository()
    {
        return $this->writeRepository(User::class);
    }

    /**
     * @param UserId $userId
     *
     * @return User
     */
    protected function getUserById(UserId $userId)
    {
        return $this->repository()->get($userId);
    }

    /**
     * @param bool $needVerification
     *
     * @return UserId
     */
    protected function dispatchCreateUserCommand($needVerification = false)
    {
        $userId = UserId::next();
        $this->commandBus()->dispatch(
            new CreateUserCommand(
                $userId->toNative(),
                'johnsnow',
                'johnsnow',
                'johnsnow@gameofthrones.com',
                [],
                $needVerification
            )
        );

        return $userId;
    }

    /**
     * @return RoleId
     */
    protected function dispatchCreateRoleCommand()
    {
        $roleId = RoleId::next();
        $this->commandBus()->dispatch(
            new CreateRoleCommand(
                $roleId->toNative(),
                'ADMIN'
            )
        );

        return $roleId;
    }

    /**
     * @param UserId $userId
     * @param RoleId $roleId
     */
    protected function dispatchAddUserRoleCommand(UserId $userId, RoleId $roleId)
    {
        $this->commandBus()->dispatch(new AddUserRoleCommand(
            $userId->toNative(),
            $roleId->toNative()
        ));
    }

    /**
     * @param StringLiteral $email
     */
    protected function dispatchResetUserPasswordRequestCommand(StringLiteral $email)
    {
        $this->commandBus()->dispatch(new ResetUserPasswordRequestCommand(
            $email->toNative()
        ));
    }

    /**
     * @param StringLiteral $emailVerificationToken
     */
    protected function dispatchVerifyUserCommand(StringLiteral $emailVerificationToken)
    {
        $this->commandBus()->dispatch(new VerifyUserCommand(
            $emailVerificationToken->toNative()
        ));
    }

    /**
     * @param UserId $userId
     */
    protected function dispatchDisableUserCommand(UserId $userId)
    {
        $this->commandBus()->dispatch(new DisableUserCommand(
            $userId->toNative()
        ));
    }

    /**
     * @param UserId $userId
     */
    protected function dispatchLogoutUserCommand(UserId $userId)
    {
        $this->commandBus()->dispatch(new LogoutUserCommand(
            $userId->toNative()
        ));
    }

    /**
     * @param string $emailOrUsername
     * @param string $password
     */
    protected function dispatchLoginUserCommand($emailOrUsername, $password)
    {
        $this->commandBus()->dispatch(new LoginUserCommand(
            $emailOrUsername,
            $password
        ));
    }

    /**
     * Test CreateUser method.
     */
    public function testCreateUser()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->then()
                ->object($this->getUserById($userId))
                    ->isNotNull()
                    ->isInstanceOf(User::class)
        ;
    }

    /**
     * Test AddUserRole method.
     */
    public function testAddUserRole()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->getUserById($userId))
            ->and($roleId = $this->dispatchCreateRoleCommand())
            ->then()
                ->integer($user->roles()->count())
                    ->isEqualTo(0)
                ->and()
                ->when($this->dispatchAddUserRoleCommand($userId, $roleId))
                ->then($user = $this->getUserById($userId))
                    ->integer($user->roles()->count())
                        ->isEqualTo(1)
        ;
    }

    /**
     * Test RemoveUserRole method.
     */
    public function testRemoveUserRole()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($roleId = $this->dispatchCreateRoleCommand())
            ->and($this->dispatchAddUserRoleCommand($userId, $roleId))
            ->and($user = $this->getUserById($userId))
            ->and(
                $command = new RemoveUserRoleCommand(
                    $userId->toNative(),
                    $roleId->toNative()
                )
            )
            ->then()
                ->integer($user->roles()->count())
                    ->isEqualTo(1)
            ->and()
            ->when($this->commandBus()->dispatch($command))
            ->then($user = $this->getUserById($userId))
                ->integer($user->roles()->count())
                    ->isEqualTo(0)
        ;
    }

    /**
     * Test ResetUserPasswordRequest method.
     */
    public function testResetUserPasswordRequest()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->getUserById($userId))
            ->then()
                ->variable($user->passwordRequestedAt())
                    ->isNull()
            ->and()
            ->when($this->dispatchResetUserPasswordRequestCommand($user->email()))
            ->then($user = $this->getUserById($userId))
                ->variable($user->passwordRequestedAt())
                    ->isNotNull()
        ;
    }

    /**
     * Test ResetUserPassword method.
     */
    public function testResetUserPassword()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->getUserById($userId))
            ->and($this->dispatchResetUserPasswordRequestCommand($user->email()))
            ->and($user = $this->getUserById($userId))
            ->and(
                $command = new ResetUserPasswordCommand(
                    $userId->toNative(),
                    $this->faker->password
                )
            )
            ->then()
                ->variable($user->passwordRequestedAt())
                    ->isNotNull()
            ->and()
            ->when($this->commandBus()->dispatch($command))
            ->then($user = $this->getUserById($userId))
                ->variable($user->passwordRequestedAt())
                    ->isNull()
        ;
    }

    /**
     * Test VerifyUser method.
     */
    public function testVerifyUser()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand(true))
            ->and($user = $this->getUserById($userId))
            ->then()
                ->boolean($user->isVerified())
                    ->isFalse()
            ->and()
            ->when($this->dispatchVerifyUserCommand($user->emailVerificationToken()))
            ->then($user = $this->getUserById($userId))
                ->boolean($user->isVerified())
                    ->isTrue()
            ->then()
                ->exception(function () {
                    $this->dispatchVerifyUserCommand(StringLiteral::fromNative('notexistingtoken'));
                })->isInstanceOf(NotFoundException::class)
        ;
    }

    /**
     * Test DisableUser method.
     */
    public function testDisableUser()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->getUserById($userId))
            ->then()
                ->boolean($user->isEnabled())
                    ->isTrue()
            ->and()
            ->when($this->dispatchDisableUserCommand($userId))
            ->then($user = $this->getUserById($userId))
                ->boolean($user->isEnabled())
                    ->isFalse()
        ;
    }

    /**
     * Test EnableUser method.
     */
    public function testEnableUser()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($this->dispatchDisableUserCommand($userId))
            ->and($user = $this->getUserById($userId))
            ->and(
                $command = new EnableUserCommand(
                    $userId->toNative()
                )
            )
            ->then()
                ->boolean($user->isEnabled())
                    ->isFalse()
            ->and()
            ->when($this->commandBus()->dispatch($command))
            ->then($user = $this->getUserById($userId))
                ->boolean($user->isEnabled())
                    ->isTrue()
        ;
    }

    /**
     * Test LoginUser method.
     */
    public function testLoginUser()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->getUserById($userId))
            ->then()
                ->variable($user->lastLogin())
                    ->isNull()
            ->and()
            ->when($this->dispatchLoginUserCommand($user->username()->toNative(), 'johnsnow'))
            ->then($user = $this->getUserById($userId))
                ->variable($user->lastLogin())
                    ->isNotNull()
            ->and()
            ->then()
                ->exception(function () use ($user) {
                    $this->dispatchLoginUserCommand($user->username()->toNative(), 'notequal');
                })
                    ->isInstanceOf(AuthenticationException::class)
            ->and()
            ->then()
                ->exception(function () {
                    $this->dispatchLoginUserCommand('notfound', 'johnsnow');
                })
                    ->isInstanceOf(NotFoundException::class)
        ;
    }

    /**
     * Test LogoutUser method.
     */
    public function testLogoutUser()
    {
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->getUserById($userId))
            ->then()
                ->variable($user->lastLogout())
                    ->isNull()
            ->and()
            ->when($this->dispatchLogoutUserCommand($userId))
            ->then($user = $this->getUserById($userId))
                ->variable($user->lastLogout())
                    ->isNotNull()
            ->and()
            ->then()
                ->exception(function () {
                    $this->dispatchLogoutUserCommand(UserId::fromNative($this->faker->uuid));
                })
                    ->isInstanceOf(NotFoundException::class)
        ;
    }
}
