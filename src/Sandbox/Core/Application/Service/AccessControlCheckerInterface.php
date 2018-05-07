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

/**
 * AccessControlChecker interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface AccessControlCheckerInterface
{
    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function isGranted(array $permissions);
}
