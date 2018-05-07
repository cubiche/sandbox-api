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

use Cubiche\Domain\System\StringLiteral;
use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * RoleWasCreatedTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleWasCreatedTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            RoleId::next(),
            StringLiteral::fromNative('Admin'),
        ];
    }
}
