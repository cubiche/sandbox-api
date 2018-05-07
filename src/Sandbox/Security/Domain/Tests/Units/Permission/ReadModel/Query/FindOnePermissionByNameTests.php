<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Permission\ReadModel\Query;

use Sandbox\Core\Domain\Tests\Units\ReadModel\Query\QueryTestTrait;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * FindOnePermissionByNameTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOnePermissionByNameTests extends TestCase
{
    use QueryTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            'app.user.create',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function validatorProvider()
    {
        return [
            [
                'assert' => false,
                'arguments' => [
                    578,
                ],
            ],
        ];
    }
}
