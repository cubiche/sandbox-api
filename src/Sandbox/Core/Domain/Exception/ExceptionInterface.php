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
 * Exception interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface ExceptionInterface
{
    /**
     * Returns the status code.
     *
     * @return int A response status code
     */
    public function getStatusCode();
}
