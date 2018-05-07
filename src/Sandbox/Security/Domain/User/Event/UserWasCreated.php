<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserWasCreated extends DomainEvent
{
    /**
     * @var StringLiteral
     */
    protected $username;

    /**
     * @var StringLiteral
     */
    protected $usernameCanonical;

    /**
     * @var StringLiteral
     */
    protected $salt;

    /**
     * @var StringLiteral
     */
    protected $password;

    /**
     * @var EmailAddress
     */
    protected $email;

    /**
     * @var EmailAddress
     */
    protected $emailCanonical;

    /**
     * @var array
     */
    protected $roles;

    /**
     * UserWasCreated constructor.
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

        $this->username = $username;
        $this->usernameCanonical = $usernameCanonical;
        $this->salt = $salt;
        $this->password = $password;
        $this->email = $email;
        $this->emailCanonical = $emailCanonical;
        $this->roles = $roles;
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->aggregateId();
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
     * @return array
     */
    public function roles()
    {
        return $this->roles;
    }
}
