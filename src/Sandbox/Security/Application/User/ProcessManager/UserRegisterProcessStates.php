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

use Cubiche\Domain\System\Enum;

/**
 * UserRegisterProcessStates class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserRegisterProcessStates extends Enum
{
    const CREATED = 'created';
    const NOT_VERIFIED = 'not_verified';
    const VERIFIED = 'verified';
}
