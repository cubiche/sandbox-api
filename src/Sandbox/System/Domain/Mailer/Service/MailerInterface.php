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

use Cubiche\Domain\System\StringLiteral;

/**
 * MailerInterface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface MailerInterface
{
    /**
     * @param StringLiteral $code
     * @param array         $recipients
     * @param array         $data
     * @param array         $attachments
     */
    public function send(StringLiteral $code, array $recipients, array $data = [], array $attachments = []);
}
