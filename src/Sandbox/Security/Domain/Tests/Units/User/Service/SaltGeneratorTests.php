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
use Sandbox\Security\Domain\User\Service\SaltGenerator;

/**
 * SaltGeneratorTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SaltGeneratorTests extends TestCase
{
    /**
     * Test Generate method.
     */
    public function testGenerate()
    {
        $this
            ->given($saltGenerator = new SaltGenerator())
            ->and($saltGenerator2 = new SaltGenerator('sha1', true, 10))
            ->and($invalidSaltGenerator = new SaltGenerator('invalid'))
            ->then()
                ->string($saltGenerator->generate())
                    ->isNotEqualTo($saltGenerator2->generate())
                ->exception(function () use ($invalidSaltGenerator) {
                    $invalidSaltGenerator->generate();
                })->isInstanceOf(\LogicException::class)
        ;
    }
}
