<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role;

use Sandbox\Security\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * RoleIdTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleIdTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
            ->implements(NativeValueObjectInterface::class)
        ;
    }
}
