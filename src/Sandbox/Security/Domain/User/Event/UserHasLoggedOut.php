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
 * UserHasLoggedOut class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserHasLoggedOut extends DomainEvent
{
    /**
     * @var DateTime
     */
    protected $loggedOutAt;

    /**
     * UserHasLoggedOut constructor.
     *
     * @param UserId   $userId
     * @param DateTime $loggedOutAt
     */
    public function __construct(UserId $userId, DateTime $loggedOutAt)
    {
        parent::__construct($userId);

        $this->loggedOutAt = $loggedOutAt;
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
    public function loggedOutAt()
    {
        return $this->loggedOutAt;
    }
}
