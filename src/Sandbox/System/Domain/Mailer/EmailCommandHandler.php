<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Mailer;

use Sandbox\System\Domain\Mailer\Command\SendEmailCommand;
use Sandbox\System\Domain\Mailer\Service\MailerInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * EmailCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EmailCommandHandler
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * LanguageCommandHandler constructor.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param SendEmailCommand $command
     */
    public function sendEmail(SendEmailCommand $command)
    {
        $this->mailer->send(
            StringLiteral::fromNative($command->code()),
            $command->recipients(),
            $command->data(),
            $command->attachments()
        );
    }
}
