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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\System\DateTime\DateTime;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\User\Event\UserHasLoggedIn;
use Sandbox\Security\Domain\User\Event\UserHasLoggedOut;
use Sandbox\Security\Domain\User\Event\UserPasswordWasReset;
use Sandbox\Security\Domain\User\Event\UserResetPasswordWasRequested;
use Sandbox\Security\Domain\User\Event\UserRoleWasAdded;
use Sandbox\Security\Domain\User\Event\UserRoleWasRemoved;
use Sandbox\Security\Domain\User\Event\UserVerificationWasRequested;
use Sandbox\Security\Domain\User\Event\UserWasCreated;
use Sandbox\Security\Domain\User\Event\UserWasDisabled;
use Sandbox\Security\Domain\User\Event\UserWasEnabled;
use Sandbox\Security\Domain\User\Event\UserWasVerified;

/**
 * User class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class User extends AggregateRoot
{
    /**
     * @var StringLiteral
     */
    protected $username;

    /**
     * Normalized representation of a username.
     *
     * @var StringLiteral
     */
    protected $usernameCanonical;

    /**
     * Random data that is used as an additional input to a function that hashes a password.
     *
     * @var StringLiteral
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var StringLiteral
     */
    protected $password;

    /**
     * @var DateTime
     */
    protected $lastLogin;

    /**
     * @var DateTime
     */
    protected $lastLogout;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var StringLiteral
     */
    protected $emailVerificationToken;

    /**
     * Random string sent to the user email address in order to verify the password resetting request.
     *
     * @var StringLiteral
     */
    protected $passwordResetToken;

    /**
     * @var DateTime
     */
    protected $passwordRequestedAt;

    /**
     * @var EmailAddress
     */
    protected $email;

    /**
     * @var EmailAddress
     */
    protected $emailCanonical;

    /**
     * @var DateTime
     */
    protected $verifiedAt;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var ArrayHashMap
     */
    protected $roles;

    /**
     * User constructor.
     *
     * @param UserId        $userId
     * @param StringLiteral $username
     * @param StringLiteral $usernameCanonical
     * @param StringLiteral $salt
     * @param StringLiteral $password
     * @param EmailAddress  $email
     * @param EmailAddress  $emailCanonical
     * @param array         $roles
     */
    public function __construct(
        UserId $userId,
        StringLiteral $username,
        StringLiteral $usernameCanonical,
        StringLiteral $salt,
        StringLiteral $password,
        EmailAddress $email,
        EmailAddress $emailCanonical,
        array $roles = array()
    ) {
        parent::__construct($userId);

        $this->recordAndApplyEvent(
            new UserWasCreated(
                $userId,
                $username,
                $usernameCanonical,
                $salt,
                $password,
                $email,
                $emailCanonical,
                $roles
            )
        );
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->id;
    }

    /**
     * @return StringLiteral
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return StringLiteral
     */
    public function usernameCanonical()
    {
        return $this->usernameCanonical;
    }

    /**
     * @return StringLiteral
     */
    public function salt()
    {
        return $this->salt;
    }

    /**
     * @return StringLiteral
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * @return DateTime
     */
    public function lastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @return DateTime
     */
    public function lastLogout()
    {
        return $this->lastLogout;
    }

    /**
     * @return StringLiteral
     */
    public function emailVerificationToken()
    {
        return $this->emailVerificationToken;
    }

    /**
     * @return StringLiteral
     */
    public function passwordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * @return DateTime
     */
    public function passwordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @return EmailAddress
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return EmailAddress
     */
    public function emailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return null !== $this->verifiedAt;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return CollectionInterface
     */
    public function roles()
    {
        return $this->roles->values();
    }

    /**
     * @param StringLiteral $passwordResetToken
     */
    public function resetPasswordRequest(StringLiteral $passwordResetToken)
    {
        $this->recordAndApplyEvent(
            new UserResetPasswordWasRequested(
                $this->userId(),
                $this->username(),
                $this->email(),
                $passwordResetToken,
                DateTime::now()
            )
        );
    }

    /**
     * @param StringLiteral $password
     */
    public function resetPassword(StringLiteral $password)
    {
        $this->recordAndApplyEvent(
            new UserPasswordWasReset($this->userId(), $password)
        );
    }

    /**
     * User has logged in.
     */
    public function login()
    {
        $this->recordAndApplyEvent(
            new UserHasLoggedIn($this->userId(), DateTime::now())
        );
    }

    /**
     * User has logged out.
     */
    public function logout()
    {
        $this->recordAndApplyEvent(
            new UserHasLoggedOut($this->userId(), DateTime::now())
        );
    }

    /**
     * @param StringLiteral $emailVerificationToken
     */
    public function verificationRequest(StringLiteral $emailVerificationToken)
    {
        $this->recordAndApplyEvent(
            new UserVerificationWasRequested(
                $this->userId(),
                $this->username(),
                $this->email(),
                $emailVerificationToken
            )
        );
    }

    /**
     * User has been verified.
     */
    public function verified()
    {
        $this->recordAndApplyEvent(
            new UserWasVerified(
                $this->userId(),
                $this->username(),
                $this->email(),
                DateTime::now()
            )
        );
    }

    /**
     * Enable the user.
     */
    public function enable()
    {
        $this->recordAndApplyEvent(
            new UserWasEnabled($this->userId())
        );
    }

    /**
     * Disable the user.
     */
    public function disable()
    {
        $this->recordAndApplyEvent(
            new UserWasDisabled($this->userId())
        );
    }

    /**
     * @param RoleId $roleId
     */
    public function addRole(RoleId $roleId)
    {
        $this->recordAndApplyEvent(
            new UserRoleWasAdded($this->userId(), $roleId)
        );
    }

    /**
     * @param RoleId $roleId
     */
    public function removeRole(RoleId $roleId)
    {
        $this->recordAndApplyEvent(
            new UserRoleWasRemoved($this->userId(), $roleId)
        );
    }

    /**
     * @param UserWasCreated $event
     */
    protected function applyUserWasCreated(UserWasCreated $event)
    {
        $this->username = $event->username();
        $this->usernameCanonical = $event->usernameCanonical();
        $this->salt = $event->salt();
        $this->password = $event->password();
        $this->email = $event->email();
        $this->emailCanonical = $event->emailCanonical();
        $this->verifiedAt = DateTime::now();
        $this->enabled = true;
        $this->roles = new ArrayHashMap();

        foreach ($event->roles() as $roleId) {
            $this->roles->set($roleId->toNative(), $roleId);
        }
    }

    /**
     * @param UserResetPasswordWasRequested $event
     */
    protected function applyUserResetPasswordWasRequested(UserResetPasswordWasRequested $event)
    {
        $this->passwordResetToken = $event->passwordResetToken();
        $this->passwordRequestedAt = $event->passwordRequestedAt();
    }

    /**
     * @param UserPasswordWasReset $event
     */
    protected function applyUserPasswordWasReset(UserPasswordWasReset $event)
    {
        $this->password = $event->password();
        $this->passwordRequestedAt = null;
        $this->passwordResetToken = null;
    }

    /**
     * @param UserHasLoggedIn $event
     */
    protected function applyUserHasLoggedIn(UserHasLoggedIn $event)
    {
        $this->lastLogin = $event->loggedInAt();
    }

    /**
     * @param UserHasLoggedOut $event
     */
    protected function applyUserHasLoggedOut(UserHasLoggedOut $event)
    {
        $this->lastLogout = $event->loggedOutAt();
    }

    /**
     * @param UserVerificationWasRequested $event
     */
    protected function applyUserVerificationWasRequested(UserVerificationWasRequested $event)
    {
        $this->emailVerificationToken = $event->emailVerificationToken();
        $this->verifiedAt = null;
        $this->enabled = false;
    }

    /**
     * @param UserWasVerified $event
     */
    protected function applyUserWasVerified(UserWasVerified $event)
    {
        $this->verifiedAt = $event->verifiedAt();
        $this->emailVerificationToken = null;
        $this->enabled = true;
    }

    /**
     * @param UserWasEnabled $event
     */
    protected function applyUserWasEnabled(UserWasEnabled $event)
    {
        $this->enabled = true;
    }

    /**
     * @param UserWasDisabled $event
     */
    protected function applyUserWasDisabled(UserWasDisabled $event)
    {
        $this->enabled = false;
    }

    /**
     * @param UserRoleWasAdded $event
     */
    protected function applyUserRoleWasAdded(UserRoleWasAdded $event)
    {
        $this->roles->set($event->roleId()->toNative(), $event->roleId());
    }

    /**
     * @param UserRoleWasRemoved $event
     */
    protected function applyUserRoleWasRemoved(UserRoleWasRemoved $event)
    {
        $this->roles->removeAt($event->roleId()->toNative());
    }
}
