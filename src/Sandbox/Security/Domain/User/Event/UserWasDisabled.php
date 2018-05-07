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

/**
 * UserWasDisabled class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserWasDisabled extends DomainEvent
{
    /**
     * UserWasDisabled constructor.
     *
     * @param UserId $userId
     */
    public function __construct(UserId $userId)
    {
        parent::__construct($userId);
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->aggregateId();
    }
}
