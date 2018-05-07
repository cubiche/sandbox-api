<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Mailer\Service;

use Sandbox\System\Domain\Mailer\Email;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * EmailFactory class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EmailFactory implements EmailFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($code, $senderName, $senderAddress, $subject = null, $content = null, $template = null)
    {
        return new Email(
            StringLiteral::fromNative($code),
            StringLiteral::fromNative($senderName),
            EmailAddress::fromNative($senderAddress),
            $subject !== null ? StringLiteral::fromNative($subject) : null,
            $content !== null ? StringLiteral::fromNative($content) : null,
            $template !== null ? StringLiteral::fromNative($template) : null
        );
    }
}
