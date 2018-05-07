<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User\ReadModel\Query;

use Sandbox\Core\Domain\Tests\Units\ReadModel\Query\QueryTestTrait;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * FindOneUserByIdTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneUserByIdTests extends TestCase
{
    use QueryTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            $this->faker->uuid,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function validatorProvider()
    {
        return array(
            array(
                'assert' => false,
                'arguments' => array(
                    '',
                ),
            ),
            array(
                'assert' => false,
                'arguments' => array(
                    123,
                ),
            ),
        );
    }
}
