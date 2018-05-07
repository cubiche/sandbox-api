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
use Sandbox\Security\Domain\User\Service\PasswordEncoder;

/**
 * PasswordEncoderTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PasswordEncoderTests extends TestCase
{
    /**
     * Test Encode method.
     */
    public function testEncode()
    {
        $this
            ->given($passwordEncoder = new PasswordEncoder())
            ->and($invalidPasswordEncoder = new PasswordEncoder('invalid'))
            ->then()
                ->string($passwordEncoder->encode('johnsnow', '123'))
                    ->isNotEqualTo($passwordEncoder->encode('johnsnow', '321'))
                ->exception(function () use ($passwordEncoder) {
                    $passwordEncoder->encode($this->faker->sentence(4012), '123');
                })->isInstanceOf(\InvalidArgumentException::class)
                ->exception(function () use ($invalidPasswordEncoder) {
                    $invalidPasswordEncoder->encode('johnsnow', '123');
                })->isInstanceOf(\LogicException::class)
        ;
    }
}
