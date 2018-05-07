<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User;

use Sandbox\Core\Domain\Exception\AuthenticationException;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\RoleId;
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
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmail;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmailVerificationToken;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByUsername;
use Sandbox\Security\Domain\User\Service\GeneratorInterface;
use Sandbox\Security\Domain\User\Service\UserFactoryInterface;
use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * UserCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var UserFactoryInterface
     */
    protected $factory;

    /**
     * @var GeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * @var QueryBus
     */
    protected $queryBus;

    /**
     * UserCommandHandler constructor.
     *
     * @param RepositoryInterface  $repository
     * @param UserFactoryInterface $factory
     * @param GeneratorInterface   $tokenGenerator
     * @param QueryBus             $queryBus
     */
    public function __construct(
        RepositoryInterface $repository,
        UserFactoryInterface $factory,
        GeneratorInterface $tokenGenerator,
        QueryBus $queryBus
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->tokenGenerator = $tokenGenerator;
        $this->queryBus = $queryBus;
    }

    /**
     * @param CreateUserCommand $command
     */
    public function createUser(CreateUserCommand $command)
    {
        $user = $this->factory->create(
            $command->userId(),
            $command->username(),
            $command->password(),
            $command->email(),
            $command->roles()
        );

        if ($command->verificationByEmail()) {
            // Generate a email verification token
            $emailVerificationToken = StringLiteral::fromNative(
                $this->tokenGenerator->generate()
            );

            $user->verificationRequest($emailVerificationToken);
        }

        $this->repository->persist($user);
    }

    /**
     * @param AddUserRoleCommand $command
     */
    public function addUserRole(AddUserRoleCommand $command)
    {
        $user = $this->findOr404($command->userId());
        $user->addRole(RoleId::fromNative($command->roleId()));

        $this->repository->persist($user);
    }

    /**
     * @param RemoveUserRoleCommand $command
     */
    public function removeUserRole(RemoveUserRoleCommand $command)
    {
        $user = $this->findOr404($command->userId());
        $user->removeRole(RoleId::fromNative($command->roleId()));

        $this->repository->persist($user);
    }

    /**
     * @param ResetUserPasswordRequestCommand $command
     */
    public function resetUserPasswordRequest(ResetUserPasswordRequestCommand $command)
    {
        // Find a read model by a given email
        $readModel = $this->findByUsernameOrEmailOr404($command->email());

        // Find the aggregate by the userId
        $user = $this->findOr404($readModel->userId()->toNative());

        // Generate a password reset token
        $passwordResetToken = StringLiteral::fromNative(
            $this->tokenGenerator->generate()
        );

        $user->resetPasswordRequest($passwordResetToken);

        $this->repository->persist($user);
    }

    /**
     * @param ResetUserPasswordCommand $command
     */
    public function resetUserPassword(ResetUserPasswordCommand $command)
    {
        $user = $this->findOr404($command->userId());

        // Encode the new plain password
        $encodePassword = $this->factory->encodePassword(
            $command->password(),
            $user->salt()->toNative()
        );

        $user->resetPassword($encodePassword);

        $this->repository->persist($user);
    }

    /**
     * @param VerifyUserCommand $command
     */
    public function verifyUser(VerifyUserCommand $command)
    {
        // Find a read model by a given emailVerificationToken
        $readModel = $this->findByEmailVerificationTokenOr404($command->emailVerificationToken());

        // Find the aggregate by the userId
        $user = $this->findOr404($readModel->userId()->toNative());
        $user->verified();

        $this->repository->persist($user);
    }

    /**
     * @param DisableUserCommand $command
     */
    public function disableUser(DisableUserCommand $command)
    {
        // Find the aggregate by the userId
        $user = $this->findOr404($command->userId());
        $user->disable();

        $this->repository->persist($user);
    }

    /**
     * @param EnableUserCommand $command
     */
    public function enableUser(EnableUserCommand $command)
    {
        // Find the aggregate by the userId
        $user = $this->findOr404($command->userId());
        $user->enable();

        $this->repository->persist($user);
    }

    /**
     * @param LoginUserCommand $command
     */
    public function loginUser(LoginUserCommand $command)
    {
        // Find a read model by a given email
        $readModel = $this->findByUsernameOrEmailOr404($command->usernameOrEmail());

        // Find the aggregate by the userId
        $user = $this->findOr404($readModel->userId()->toNative());

        // Encode the given password with the user salt value
        $encodePassword = $this->factory->encodePassword(
            $command->password(),
            $user->salt()->toNative()
        );

        if (!$user->password()->equals($encodePassword)) {
            throw new AuthenticationException('The password you entered did not match');
        }

        $user->login();

        $this->repository->persist($user);
    }

    /**
     * @param LogoutUserCommand $command
     */
    public function logoutUser(LogoutUserCommand $command)
    {
        $user = $this->findOr404($command->userId());
        $user->logout();

        $this->repository->persist($user);
    }

    /**
     * @param string $userId
     *
     * @return User
     */
    private function findOr404($userId)
    {
        $user = $this->repository->get(UserId::fromNative($userId));
        if ($user === null) {
            throw new NotFoundException(sprintf(
                'There is no user with id: %s',
                $userId
            ));
        }

        return $user;
    }

    /**
     * @param string $emailVerificationToken
     *
     * @return \Sandbox\Security\Domain\User\ReadModel\User
     */
    private function findByEmailVerificationTokenOr404($emailVerificationToken)
    {
        $user = $this->queryBus->dispatch(new FindOneUserByEmailVerificationToken($emailVerificationToken));
        if ($user === null) {
            throw new NotFoundException(sprintf(
                'There is no user with emailVerificationToken: %s',
                $emailVerificationToken
            ));
        }

        return $user;
    }

    /**
     * @param string $usernameOrEmail
     *
     * @return \Sandbox\Security\Domain\User\ReadModel\User
     */
    private function findByUsernameOrEmailOr404($usernameOrEmail)
    {
        /** @var \Sandbox\Security\Domain\User\ReadModel\User $user */
        $user = $this->queryBus->dispatch(new FindOneUserByUsername($usernameOrEmail));
        if ($user === null) {
            try {
                $user = $this->queryBus->dispatch(new FindOneUserByEmail($usernameOrEmail));
            } catch (\InvalidArgumentException $e) {
                // An exception is thrown if you try to query by email with a value that doesn't is an email
            }
        }

        if ($user === null || !$user->isEnabled()) {
            throw new NotFoundException(sprintf(
                'There is no user with username or email: %s',
                $usernameOrEmail
            ));
        }

        return $user;
    }
}
