<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\ReadModel\Projection;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\ReadModel\Role;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\User\Event\UserPasswordWasReset;
use Sandbox\Security\Domain\User\Event\UserResetPasswordWasRequested;
use Sandbox\Security\Domain\User\Event\UserRoleWasAdded;
use Sandbox\Security\Domain\User\Event\UserRoleWasRemoved;
use Sandbox\Security\Domain\User\Event\UserVerificationWasRequested;
use Sandbox\Security\Domain\User\Event\UserWasCreated;
use Sandbox\Security\Domain\User\Event\UserWasDisabled;
use Sandbox\Security\Domain\User\Event\UserWasEnabled;
use Sandbox\Security\Domain\User\Event\UserWasVerified;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Domain\User\UserId;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * UserProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * @var QueryRepositoryInterface
     */
    protected $roleRepository;

    /**
     * UserProjector constructor.
     *
     * @param QueryRepositoryInterface $repository
     * @param QueryRepositoryInterface $roleRepository
     */
    public function __construct(QueryRepositoryInterface $repository, QueryRepositoryInterface $roleRepository)
    {
        $this->repository = $repository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param UserWasCreated $event
     */
    public function whenUserWasCreated(UserWasCreated $event)
    {
        $readModel = new User(
            $event->userId(),
            $event->usernameCanonical(),
            $event->emailCanonical(),
            true,
            true
        );

        foreach ($event->roles() as $roleId) {
            $role = $this->findRoleOr404($roleId);
            foreach ($role->permissions() as $permission) {
                $readModel->addPermission($permission);
            }
        }

        $this->repository->persist($readModel);
    }

    /**
     * @param UserRoleWasAdded $event
     */
    public function whenUserRoleWasAdded(UserRoleWasAdded $event)
    {
        $readModel = $this->findOr404($event->userId());

        $role = $this->findRoleOr404($event->roleId());
        foreach ($role->permissions() as $permission) {
            $readModel->addPermission($permission);
        }

        $this->repository->persist($readModel);
    }

    /**
     * @param UserRoleWasRemoved $event
     */
    public function whenUserRoleWasRemoved(UserRoleWasRemoved $event)
    {
        $readModel = $this->findOr404($event->userId());

        $role = $this->findRoleOr404($event->roleId());
        foreach ($role->permissions() as $permission) {
            $readModel->removePermission($permission);
        }

        $this->repository->persist($readModel);
    }

    /**
     * @param UserVerificationWasRequested $event
     */
    public function whenUserVerificationWasRequested(UserVerificationWasRequested $event)
    {
        $readModel = $this->findOr404($event->userId());
        $readModel->needVerification($event->emailVerificationToken());

        $this->repository->persist($readModel);
    }

    /**
     * @param UserWasVerified $event
     */
    public function whenUserWasVerified(UserWasVerified $event)
    {
        $readModel = $this->findOr404($event->userId());
        $readModel->verified();

        $this->repository->persist($readModel);
    }

    /**
     * @param UserResetPasswordWasRequested $event
     */
    public function whenUserResetPasswordWasRequested(UserResetPasswordWasRequested $event)
    {
        $readModel = $this->findOr404($event->userId());
        $readModel->setPasswordResetToken($event->passwordResetToken());

        $this->repository->persist($readModel);
    }

    /**
     * @param UserPasswordWasReset $event
     */
    public function whenUserPasswordWasReset(UserPasswordWasReset $event)
    {
        $readModel = $this->findOr404($event->userId());
        $readModel->setPasswordResetToken(null);

        $this->repository->persist($readModel);
    }

    /**
     * @param UserWasDisabled $event
     */
    public function whenUserWasDisabled(UserWasDisabled $event)
    {
        $readModel = $this->findOr404($event->userId());
        $readModel->disable();

        $this->repository->persist($readModel);
    }

    /**
     * @param UserWasEnabled $event
     */
    public function whenUserWasEnabled(UserWasEnabled $event)
    {
        $readModel = $this->findOr404($event->userId());
        $readModel->enable();

        $this->repository->persist($readModel);
    }

    /**
     * @param UserId $userId
     *
     * @return User
     */
    private function findOr404(UserId $userId)
    {
        /** @var User $user */
        $user = $this->repository->get($userId);
        if ($user === null) {
            throw new NotFoundException(sprintf(
                'There is no user with id: %s',
                $userId
            ));
        }

        return $user;
    }

    /**
     * @param RoleId $roleId
     *
     * @return Role
     */
    private function findRoleOr404(RoleId $roleId)
    {
        /** @var Role $role */
        $role = $this->roleRepository->get($roleId);
        if ($role === null) {
            throw new NotFoundException(sprintf(
                'There is no role with id: %s',
                $roleId
            ));
        }

        return $role;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserWasCreated::class => array('whenUserWasCreated', 250),
            UserRoleWasAdded::class => array('whenUserRoleWasAdded', 250),
            UserRoleWasRemoved::class => array('whenUserRoleWasRemoved', 250),
            UserVerificationWasRequested::class => array('whenUserVerificationWasRequested', 250),
            UserWasVerified::class => array('whenUserWasVerified', 250),
            UserWasDisabled::class => array('whenUserWasDisabled', 250),
            UserWasEnabled::class => array('whenUserWasEnabled', 250),
            UserResetPasswordWasRequested::class => array('whenUserResetPasswordWasRequested', 250),
            UserPasswordWasReset::class => array('whenUserPasswordWasReset', 250),
        );
    }
}
