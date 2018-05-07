<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User\Service;

use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\Service\Canonicalizer;

/**
 * CanonicalizerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CanonicalizerTests extends TestCase
{
    /**
     * Test Canonicalize method.
     */
    public function testCanonicalize()
    {
        $this
            ->given($canonicalizer = new Canonicalizer())
            ->then()
                ->variable($canonicalizer->canonicalize(null))
                    ->isNull()
                ->string($canonicalizer->canonicalize('JohnSnow'))
                    ->isEqualTo('johnsnow')
        ;
    }
}
