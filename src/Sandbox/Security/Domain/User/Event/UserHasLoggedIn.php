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

/**
 * UserHasLoggedIn class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserHasLoggedIn extends DomainEvent
{
    /**
     * @var DateTime
     */
    protected $loggedInAt;

    /**
     * UserHasLoggedIn constructor.
     *
     * @param UserId   $userId
     * @param DateTime $loggedInAt
     */
    public function __construct(UserId $userId, DateTime $loggedInAt)
    {
        parent::__construct($userId);

        $this->loggedInAt = $loggedInAt;
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->aggregateId();
    }

    /**
     * @return DateTime
     */
    public function loggedInAt()
    {
        return $this->loggedInAt;
    }
}
