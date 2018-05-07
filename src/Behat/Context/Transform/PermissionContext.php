<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Transform;

use Behat\Behat\Context\Context;

/**
 * PermissionContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class PermissionContext implements Context
{
    /**
     * @Transform /^permission(?:|s) "([^"]+)"$/
     * @Transform /^"([^"]+)" permission(?:|s)$/
     * @Transform :permission
     *
     * @param string $permissionName
     *
     * @return array
     */
    public function getPermissionsByName($permissionName)
    {
        return explode(',', str_replace(' ', '', $permissionName));
    }
}
