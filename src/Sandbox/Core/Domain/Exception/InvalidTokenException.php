<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Domain\Exception;

/**
 * InvalidTokenException class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidTokenException extends AuthenticationException
{
    /**
     * InvalidTokenException constructor.
     *
     * @param string $message
     */
    public function __construct($message = 'Invalid JWT Token')
    {
        parent::__construct($message);
    }
}
