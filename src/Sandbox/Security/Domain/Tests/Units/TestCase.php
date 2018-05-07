<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units;

use Sandbox\Core\Domain\Tests\Units\SettingCommandBusTrait;
use Sandbox\Core\Domain\Tests\Units\SettingEventBusTrait;
use Sandbox\Core\Domain\Tests\Units\SettingProjectorTrait;
use Sandbox\Core\Domain\Tests\Units\SettingQueryRepositoryTrait;
use Sandbox\Core\Domain\Tests\Units\SettingWriteRepositoryTrait;
use Sandbox\Core\Domain\Tests\Units\TestCase as BaseTestCase;
use Sandbox\Security\Domain\Permission\ReadModel\PermissionQueryHandler;
use Sandbox\Security\Domain\Permission\ReadModel\Query\FindAllPermissions;
use Sandbox\Security\Domain\Permission\ReadModel\Query\FindOnePermissionByName;
use Sandbox\Security\Domain\Role\Command\AddPermissionToRoleCommand;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\Command\RemovePermissionFromRoleCommand;
use Sandbox\Security\Domain\Role\ReadModel\Projection\RoleProjector;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindAllRoles;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleById;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleByName;
use Sandbox\Security\Domain\Role\ReadModel\Role as ReadModelRole;
use Sandbox\Security\Domain\Role\ReadModel\RoleQueryHandler;
use Sandbox\Security\Domain\Role\Role;
use Sandbox\Security\Domain\Role\RoleCommandHandler;
use Sandbox\Security\Domain\Role\Validator\Asserter as RoleAsserter;
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
use Sandbox\Security\Domain\User\ReadModel\Projection\UserProjector;
use Sandbox\Security\Domain\User\ReadModel\Query\FindAllUsers;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmail;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmailVerificationToken;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserById;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByPasswordResetToken;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByUsername;
use Sandbox\Security\Domain\User\ReadModel\User as ReadModelUser;
use Sandbox\Security\Domain\User\ReadModel\UserQueryHandler;
use Sandbox\Security\Domain\User\Service\Canonicalizer;
use Sandbox\Security\Domain\User\Service\PasswordEncoder;
use Sandbox\Security\Domain\User\Service\SaltGenerator;
use Sandbox\Security\Domain\User\Service\TokenGenerator;
use Sandbox\Security\Domain\User\Service\UserFactory;
use Sandbox\Security\Domain\User\User;
use Sandbox\Security\Domain\User\UserCommandHandler;
use Sandbox\Security\Domain\User\Validator\Asserter as UserAsserter;
use Cubiche\Core\Validator\Validator;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;

/**
 * TestCase class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TestCase extends BaseTestCase
{
    use SettingCommandBusTrait;
    use SettingWriteRepositoryTrait;
    use SettingQueryRepositoryTrait;
    use SettingPermissionRepositoryTrait;
    use SettingEventBusTrait, SettingProjectorTrait {
        SettingEventBusTrait::eventDispatcher insteadof SettingProjectorTrait;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeTestMethod($method)
    {
        DomainEventPublisher::set($this->eventBus());
        $this->registerValidatorAsserters();
    }

    /**
     * {@inheritdoc}
     */
    protected function registerValidatorAsserters()
    {
        parent::registerValidatorAsserters();

        $securityContextMock = $this->newMockInstance('Sandbox\Security\Domain\User\Service\SecurityContextInterface');

        $roleAsserter = new RoleAsserter($this->queryBus(), $securityContextMock);
        $userAsserter = new UserAsserter($this->queryBus());

        Validator::registerValidator('uniqueRoleName', array($roleAsserter, 'uniqueRoleName'));
        Validator::registerValidator('uniqueUsername', array($userAsserter, 'uniqueUsername'));
        Validator::registerValidator('uniqueEmail', array($userAsserter, 'uniqueEmail'));
    }

    /**
     * @return array
     */
    protected function commandHandlers()
    {
        $roleCommandHandler = new RoleCommandHandler(
            $this->writeRepository(Role::class),
            $this->permissionRepository()
        );

        $userCommandHandler = new UserCommandHandler(
            $this->writeRepository(User::class),
            new UserFactory(new Canonicalizer(), new PasswordEncoder(), new SaltGenerator()),
            new TokenGenerator(),
            $this->queryBus()
        );

        return [
            CreateRoleCommand::class => $roleCommandHandler,
            AddPermissionToRoleCommand::class => $roleCommandHandler,
            RemovePermissionFromRoleCommand::class => $roleCommandHandler,
            CreateUserCommand::class => $userCommandHandler,
            AddUserRoleCommand::class => $userCommandHandler,
            RemoveUserRoleCommand::class => $userCommandHandler,
            ResetUserPasswordCommand::class => $userCommandHandler,
            ResetUserPasswordRequestCommand::class => $userCommandHandler,
            VerifyUserCommand::class => $userCommandHandler,
            DisableUserCommand::class => $userCommandHandler,
            EnableUserCommand::class => $userCommandHandler,
            LoginUserCommand::class => $userCommandHandler,
            LogoutUserCommand::class => $userCommandHandler,
        ];
    }

    /**
     * @return array
     */
    protected function commandValidatorHandlers()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function queryHandlers()
    {
        $roleQueryHandler = new RoleQueryHandler(
            $this->queryRepository(ReadModelRole::class)
        );

        $permissionQueryHandler = new PermissionQueryHandler(
            $this->permissionRepository()
        );

        $userQueryHandler = new UserQueryHandler(
            $this->queryRepository(ReadModelUser::class),
            new Canonicalizer()
        );

        return [
            FindAllRoles::class => $roleQueryHandler,
            FindOneRoleById::class => $roleQueryHandler,
            FindOneRoleByName::class => $roleQueryHandler,
            FindAllPermissions::class => $permissionQueryHandler,
            FindOnePermissionByName::class => $permissionQueryHandler,
            FindAllUsers::class => $userQueryHandler,
            FindOneUserByEmail::class => $userQueryHandler,
            FindOneUserByUsername::class => $userQueryHandler,
            FindOneUserByEmailVerificationToken::class => $userQueryHandler,
            FindOneUserByPasswordResetToken::class => $userQueryHandler,
            FindOneUserById::class => $userQueryHandler,
        ];
    }

    /**
     * @return array
     */
    protected function queryValidatorHandlers()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function eventSubscribers()
    {
        $roleRepository = $this->queryRepository(ReadModelRole::class);

        $roleProjector = new RoleProjector(
            $roleRepository,
            $this->permissionRepository()
        );

        $userProjector = new UserProjector(
            $this->queryRepository(ReadModelUser::class),
            $roleRepository
        );

        return [
            $roleProjector,
            $userProjector,
        ];
    }
}
