<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User\Event;

use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserWasCreatedTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserWasCreatedTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            UserId::next(),
            StringLiteral::fromNative('johnsnow'),
            StringLiteral::fromNative('johnsnow'),
            StringLiteral::fromNative('salt'),
            StringLiteral::fromNative('johnsnow'),
            EmailAddress::fromNative('johnsnow@gameofthrones.com'),
            EmailAddress::fromNative('johnsnow@gameofthrones.com'),
            [],
        ];
    }
}
