<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User\ReadModel\Projection;

use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\ReadModel\Role;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\Command\AddUserRoleCommand;
use Sandbox\Security\Domain\User\Command\CreateUserCommand;
use Sandbox\Security\Domain\User\Command\DisableUserCommand;
use Sandbox\Security\Domain\User\Command\EnableUserCommand;
use Sandbox\Security\Domain\User\Command\RemoveUserRoleCommand;
use Sandbox\Security\Domain\User\Command\ResetUserPasswordCommand;
use Sandbox\Security\Domain\User\Command\ResetUserPasswordRequestCommand;
use Sandbox\Security\Domain\User\Command\VerifyUserCommand;
use Sandbox\Security\Domain\User\ReadModel\Projection\UserProjector;
use Sandbox\Security\Domain\User\ReadModel\User as ReadModelUser;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserProjectorTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserProjectorTests extends TestCase
{
    /**
     * @return QueryRepositoryInterface
     */
    protected function repository()
    {
        return $this->queryRepository(ReadModelUser::class);
    }

    /**
     * @return QueryRepositoryInterface
     */
    protected function roleRepository()
    {
        return $this->queryRepository(Role::class);
    }

    /**
     * @param bool  $verificationByEmail
     * @param array $roles
     *
     * @return UserId
     */
    protected function dispatchCreateUserCommand($verificationByEmail = false, $roles = [])
    {
        $userId = UserId::next();
        $this->commandBus()->dispatch(new CreateUserCommand(
            $userId->toNative(),
            $this->faker->userName,
            'johnsnow',
            $this->faker->email,
            $roles,
            $verificationByEmail
        ));

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
                'ADMIN',
                ['app']
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
     * @param UserId $userId
     * @param RoleId $roleId
     */
    protected function dispatchRemoveUserRoleCommand(UserId $userId, RoleId $roleId)
    {
        $this->commandBus()->dispatch(new RemoveUserRoleCommand(
            $userId->toNative(),
            $roleId->toNative()
        ));
    }

    /**
     * @param StringLiteral $emailVerificationToken
     */
    protected function dispatchVerifyUserCommand(StringLiteral $emailVerificationToken)
    {
        $this->commandBus()->dispatch(new VerifyUserCommand($emailVerificationToken->toNative()));
    }

    /**
     * @param EmailAddress $email
     */
    protected function dispatchResetUserPasswordRequestCommand(EmailAddress $email)
    {
        $this->commandBus()->dispatch(new ResetUserPasswordRequestCommand($email->toNative()));
    }

    /**
     * @param UserId        $userId
     * @param StringLiteral $password
     */
    protected function dispatchResetUserPasswordCommand(UserId $userId, StringLiteral $password)
    {
        $this->commandBus()->dispatch(new ResetUserPasswordCommand(
            $userId->toNative(),
            $password->toNative()
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
    protected function dispatchEnableUserCommand(UserId $userId)
    {
        $this->commandBus()->dispatch(new EnableUserCommand(
            $userId->toNative()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function testCreate()
    {
        $this
            ->given(
                $projector = new UserProjector(
                    $this->repository(),
                    $this->roleRepository()
                )
            )
            ->then()
                ->array($projector->getSubscribedEvents())
                    ->isNotEmpty()
        ;
    }

    /**
     * Test WhenUserWasCreated method.
     */
    public function testWhenUserWasCreated()
    {
        $this
            ->boolean($this->repository()->isEmpty())
                ->isTrue()
            ->and()
            ->when($roleId = $this->dispatchCreateRoleCommand())
            ->and($this->dispatchCreateUserCommand(false, [$roleId->toNative()]))
            ->then()
                ->boolean($this->repository()->isEmpty())
                    ->isFalse()
        ;
    }

    /**
     * Test WhenUserRoleWasAdded method.
     */
    public function testWhenUserRoleWasAdded()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($roleId = $this->dispatchCreateRoleCommand())
            ->and($user = $this->repository()->get($userId))
            ->then()
                ->boolean($user->permissions()->isEmpty())
                    ->isTrue()
            ->when($this->dispatchAddUserRoleCommand($userId, $roleId))
            ->and($user = $this->repository()->get($userId))
            ->then()
                ->boolean($user->permissions()->isEmpty())
                    ->isFalse()
            ->and()
                ->exception(function () use ($userId) {
                    $this->dispatchAddUserRoleCommand($userId, RoleId::next());
                })
                    ->isInstanceOf(NotFoundException::class)
        ;
    }

    /**
     * Test WhenUserRoleWasRemoved method.
     */
    public function testWhenUserRoleWasRemoved()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($roleId = $this->dispatchCreateRoleCommand())
            ->and($this->dispatchAddUserRoleCommand($userId, $roleId))
            ->and($user = $this->repository()->get($userId))
            ->then()
                ->boolean($user->permissions()->isEmpty())
                    ->isFalse()
            ->when($this->dispatchRemoveUserRoleCommand($userId, $roleId))
            ->and($user = $this->repository()->get($userId))
            ->then()
                ->boolean($user->permissions()->isEmpty())
                    ->isTrue()
        ;
    }

    /**
     * Test WhenUserVerificationWasRequested method.
     */
    public function testWhenUserVerificationWasRequested()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->repository()->get($userId))
            ->then()
                ->boolean($user->isVerified())
                    ->isTrue()
            ->when($unverifiedUserId = $this->dispatchCreateUserCommand(true))
            ->then($user = $this->repository()->get($unverifiedUserId))
                ->boolean($user->isVerified())
                    ->isFalse()
        ;
    }

    /**
     * Test WhenUserWasVerified method.
     */
    public function testWhenUserWasVerified()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand(true))
            ->and($user = $this->repository()->get($userId))
            ->then()
                ->boolean($user->isVerified())
                    ->isFalse()
            ->when($this->dispatchVerifyUserCommand($user->emailVerificationToken()))
            ->then($user = $this->repository()->get($userId))
                ->boolean($user->isVerified())
                    ->isTrue()
        ;
    }

    /**
     * Test WhenUserResetPasswordWasRequested method.
     */
    public function testWhenUserResetPasswordWasRequested()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->repository()->get($userId))
            ->then()
                ->variable($user->passwordResetToken())
                    ->isNull()
            ->when($this->dispatchResetUserPasswordRequestCommand($user->email()))
            ->then($user = $this->repository()->get($userId))
                ->variable($user->passwordResetToken())
                    ->isNotNull()
        ;
    }

    /**
     * Test WhenUserPasswordWasReset method.
     */
    public function testWhenUserPasswordWasReset()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($user = $this->repository()->get($userId))
            ->and($this->dispatchResetUserPasswordRequestCommand($user->email()))
            ->then($user = $this->repository()->get($userId))
                ->variable($user->passwordResetToken())
                    ->isNotNull()
            ->when($this->dispatchResetUserPasswordCommand($userId, StringLiteral::fromNative('newpassword')))
            ->then($user = $this->repository()->get($userId))
                ->variable($user->passwordResetToken())
                    ->isNull()
        ;
    }

    /**
     * Test WhenUserWasDisabled method.
     */
    public function testWhenUserWasDisabled()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->then($user = $this->repository()->get($userId))
                ->boolean($user->isEnabled())
                    ->istrue()
            ->when($this->dispatchDisableUserCommand($userId))
            ->then($user = $this->repository()->get($userId))
                ->boolean($user->isEnabled())
                    ->isFalse()
        ;
    }

    /**
     * Test WhenUserWasEnabled method.
     */
    public function testWhenUserWasEnabled()
    {
        /* @var ReadModelUser $user */
        $this
            ->given($userId = $this->dispatchCreateUserCommand())
            ->and($this->dispatchDisableUserCommand($userId))
            ->then($user = $this->repository()->get($userId))
                ->boolean($user->isEnabled())
                    ->isFalse()
            ->when($this->dispatchEnableUserCommand($userId))
            ->then($user = $this->repository()->get($userId))
                ->boolean($user->isEnabled())
                    ->istrue()
        ;
    }
}
