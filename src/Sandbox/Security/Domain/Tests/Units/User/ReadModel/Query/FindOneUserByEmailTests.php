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
 * FindOneUserByEmailTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneUserByEmailTests extends TestCase
{
    use QueryTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            'johnsnow@gameofthrones.com',
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
                    'notanemail',
                ),
            ),
        );
    }
}
