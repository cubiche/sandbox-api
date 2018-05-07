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
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * FindOneRoleByIdTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneRoleByIdTests extends TestCase
{
    use QueryTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            RoleId::nextUUIDValue(),
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
                    'foo',
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
