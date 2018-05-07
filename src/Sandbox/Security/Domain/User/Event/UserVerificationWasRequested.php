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

use Sandbox\Security\Domain\User\UserId;
use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * UserVerificationWasRequested class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserVerificationWasRequested extends DomainEvent
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
     * UserVerificationWasRequested constructor.
     *
     * @param UserId        $userId
     * @param StringLiteral $username
     * @param EmailAddress  $email
     * @param StringLiteral $emailVerificationToken
     */
    public function __construct(
        UserId $userId,
        StringLiteral $username,
        EmailAddress $email,
        StringLiteral $emailVerificationToken
    ) {
        parent::__construct($userId);

        $this->username = $username;
        $this->email = $email;
        $this->emailVerificationToken = $emailVerificationToken;
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
}
