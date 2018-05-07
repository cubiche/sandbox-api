<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Mailer\Event;

use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * EmailWasSentTests class.
 *
 * Generated by TestGenerator on 2018-02-15 at 10:51:52.
 */
class EmailWasSentTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            StringLiteral::fromNative('app.user.reset_password'),
            StringLiteral::fromNative('Company Name'),
            EmailAddress::fromNative('test@example.com'),
            array('ivannis.suarez@gmail.com', 'ivan@cubiche.com'),
        );
    }
}