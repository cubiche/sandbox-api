<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role\ReadModel\Query;

use Sandbox\Core\Domain\Tests\Units\ReadModel\Query\QueryTestTrait;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * FindOneRoleByNameTests class.
 *
 * Generated by TestGenerator on 2018-04-04 at 11:21:39.
 */
class FindOneRoleByNameTests extends TestCase
{
    use QueryTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            'ROLE_NAME',
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
