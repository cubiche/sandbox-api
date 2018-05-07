<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Application\Service;

use Sandbox\Core\Application\Token;

/**
 * TokenEncoder interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface TokenEncoderInterface
{
    /**
     * @param string $userId
     * @param string $email
     * @param array  $permissions
     *
     * @return Token
     */
    public function encode($userId, $email, array $permissions);
}
