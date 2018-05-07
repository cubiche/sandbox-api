<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Language\ReadModel\Query;

use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Core\Cqrs\Query\QueryInterface;

/**
 * FindAllLanguagesTests class.
 *
 * Generated by TestGenerator on 2018-02-15 at 10:51:52.
 */
class FindAllLanguagesTests extends TestCase
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
