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

use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\UserId;
use Cubiche\Domain\System\DateTime\DateTime;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * UserWasVerifiedTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserWasVerifiedTests extends TestCase
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
            EmailAddress::fromNative('johnsnow@gameofthrones.com'),
            DateTime::now(),
        ];
    }
}
