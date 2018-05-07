<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User\Command;

use Sandbox\Core\Domain\Tests\Units\Command\CommandTestTrait;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\UserId;

/**
 * ResetUserPasswordCommandTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ResetUserPasswordCommandTests extends TestCase
{
    use CommandTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            UserId::nextUUIDValue(),
            'johnsnow',
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
                    UserId::nextUUIDValue(),
                    1,
                ],
            ],
            [
                'assert' => false,
                'arguments' => [
                    null,
                    'johnsnow',
                ],
            ],
        ];
    }
}
