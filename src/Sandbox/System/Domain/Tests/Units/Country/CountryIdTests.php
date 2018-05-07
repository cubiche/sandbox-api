<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Country;

use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * CountryIdTests class.
 *
 * Generated by TestGenerator on 2018-02-23 at 10:48:23.
 */
class CountryIdTests extends TestCase
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