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
use Cubiche\Domain\System\DateTime\DateTime;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * UserWasVerified class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserWasVerified extends DomainEvent
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
     * @var DateTime
     */
    protected $verifiedAt;

    /**
     * UserWasVerified constructor.
     *
     * @param UserId        $userId
     * @param StringLiteral $username
     * @param EmailAddress  $email
     * @param DateTime      $verifiedAt
     */
    public function __construct(UserId $userId, StringLiteral $username, EmailAddress $email, DateTime $verifiedAt)
    {
        parent::__construct($userId);

        $this->username = $username;
        $this->email = $email;
        $this->verifiedAt = $verifiedAt;
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
     * @return DateTime
     */
    public function verifiedAt()
    {
        return $this->verifiedAt;
    }
}
