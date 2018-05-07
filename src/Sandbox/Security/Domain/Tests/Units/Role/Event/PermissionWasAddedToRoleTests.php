<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role\Event;

use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Cubiche\Domain\System\StringLiteral;

/**
 * PermissionWasAddedToRoleTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PermissionWasAddedToRoleTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            RoleId::next(),
            StringLiteral::fromNative('app.user.create'),
        ];
    }
}
