<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role\Command;

use Sandbox\Core\Domain\Tests\Units\Command\CommandTestTrait;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * CreateRoleCommandTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateRoleCommandTests extends TestCase
{
    use CommandTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            RoleId::next()->toNative(),
            $this->faker->word(),
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
                    $this->faker->word(),
                    $this->faker->word(),
                ],
            ],
            [
                'assert' => false,
                'arguments' => [
                    RoleId::next()->toNative(),
                    '',
                ],
            ],
        ];
    }
}
