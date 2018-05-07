<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\User;

use Cubiche\Domain\System\Enum;

/**
 * UserEmailType class.
 *
 * @method static UserEmailType USER_VERIFICATION_REQUEST()
 * @method static UserEmailType USER_REGISTRATION_SUCCESS()
 * @method static UserEmailType USER_RESET_PASSWORD_REQUEST()
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserEmailType extends Enum
{
    const USER_VERIFICATION_REQUEST = 'user_verification_request';
    const USER_REGISTRATION_SUCCESS = 'user_registration_success';
    const USER_RESET_PASSWORD_REQUEST = 'user_reset_password_request';
}
