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

/**
 * UserPasswordWasReset class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserPasswordWasReset extends DomainEvent
{
    /**
     * Encrypted password.
     *
     * @var StringLiteral
     */
    protected $password;

    /**
     * UserPasswordWasReset constructor.
     *
     * @param UserId        $userId
     * @param StringLiteral $password
     */
    public function __construct(UserId $userId, StringLiteral $password)
    {
        parent::__construct($userId);

        $this->password = $password;
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
    public function password()
    {
        return $this->password;
    }
}
