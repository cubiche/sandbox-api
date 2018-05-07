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
 * AuthenticationException class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AuthenticationException extends Exception
{
    /**
     * AuthenticationException constructor.
     *
     * @param string|null $message
     */
    public function __construct($message = null)
    {
        parent::__construct(403, $message);
    }
}
