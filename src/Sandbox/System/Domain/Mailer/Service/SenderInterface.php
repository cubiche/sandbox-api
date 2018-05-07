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

/**
 * SenderInterface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface SenderInterface
{
    /**
     * @param array  $recipients
     * @param string $senderAddress
     * @param string $senderName
     * @param string $subject
     * @param string $body
     * @param array  $data
     * @param array  $attachments
     */
    public function send(
        array $recipients,
        $senderAddress,
        $senderName,
        $subject,
        $body,
        array $data,
        array $attachments = []
    );
}
