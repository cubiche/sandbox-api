<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Mailer\Service;

use Sandbox\System\Domain\Mailer\Service\SenderInterface;

/**
 * SwiftMailerSender class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SwiftMailerSender implements SenderInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function send(
        array $recipients,
        $senderAddress,
        $senderName,
        $subject,
        $body,
        array $data,
        array $attachments = []
    ) {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom([$senderAddress => $senderName])
            ->setTo($recipients)
        ;

        $message->setBody($body, 'text/html');

        foreach ($attachments as $attachment) {
            $file = \Swift_Attachment::fromPath($attachment);

            $message->attach($file);
        }

        $this->mailer->send($message);
    }
}
