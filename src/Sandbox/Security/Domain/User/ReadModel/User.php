<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\ReadModel;

use Sandbox\Security\Domain\User\UserId;
use Cubiche\Core\Collections\ArrayCollection\ArraySet;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * User class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class User extends Entity implements ReadModelInterface
{
    /**
     * @var StringLiteral
     */
    protected $username;

    /**
     * @var EmailAddress
     */
    protected $email;

    /**
     * @var StringLiteral
     */
    protected $emailVerificationToken;

    /**
     * @var StringLiteral
     */
    protected $passwordResetToken;

    /**
     * @var bool
     */
    protected $verified;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var ArraySet
     */
    protected $permissions;

    /**
     * User constructor.
     *
     * @param UserId        $userId
     * @param StringLiteral $username
     * @param EmailAddress  $email
     * @param bool          $verified
     * @param bool          $enabled
     */
    public function __construct(
        UserId $userId,
        StringLiteral $username,
        EmailAddress $email,
        $verified,
        $enabled
    ) {
        parent::__construct($userId);

        $this->username = $username;
        $this->email = $email;
        $this->verified = $verified;
        $this->enabled = $enabled;
        $this->permissions = new ArraySet();
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
     * @return EmailAddress
     */
    public function email()
    {
        return $this->email;
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
     * @param StringLiteral $passwordResetToken
     */
    public function setPasswordResetToken(StringLiteral $passwordResetToken = null)
    {
        $this->passwordResetToken = $passwordResetToken;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable the user.
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disable the user.
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * The user has to be verified.
     *
     * @param StringLiteral $emailVerificationToken
     */
    public function needVerification(StringLiteral $emailVerificationToken)
    {
        $this->emailVerificationToken = $emailVerificationToken;
        $this->verified = false;
        $this->disable();
    }

    /**
     * The user has been verified.
     */
    public function verified()
    {
        $this->emailVerificationToken = null;
        $this->verified = true;
        $this->enable();
    }

    /**
     * @return ArraySet
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * @param StringLiteral $permission
     */
    public function addPermission(StringLiteral $permission)
    {
        $this->permissions->add($permission);
    }

    /**
     * @param StringLiteral $permission
     */
    public function removePermission(StringLiteral $permission)
    {
        $this->permissions->remove($permission);
    }
}
