<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User\Event;

use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserRoleWasAddedTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserRoleWasAddedTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            UserId::next(),
            RoleId::next(),
        ];
    }
}
