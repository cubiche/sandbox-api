<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\User\ProcessManager;

use Sandbox\Security\Domain\User\UserId;
use Cubiche\Domain\ProcessManager\ProcessManagerState;
use Cubiche\Domain\System\StringLiteral;

/**
 * UserRegisterProcessStates class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class UserRegisterProcessState extends ProcessManagerState
{
    /**
     * UserCreationProcessState constructor.
     *
     * @param UserId $userId
     */
    public function __construct(UserId $userId)
    {
        parent::__construct($userId, StringLiteral::fromNative(UserRegisterProcessStates::CREATED));
    }
}
