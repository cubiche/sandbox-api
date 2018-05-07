<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Country\ReadModel\Query;

use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Core\Cqrs\Query\QueryInterface;

/**
 * FindAllCountriesTests class.
 *
 * Generated by TestGenerator on 2018-02-23 at 10:48:23.
 */
class FindAllCountriesTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(QueryInterface::class)
        ;
    }
}