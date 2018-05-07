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

use Sandbox\System\Domain\Mailer\Event\EmailWasSent;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\System\StringLiteral;

/**
 * Mailer class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Mailer implements MailerInterface
{
    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var SenderInterface
     */
    protected $sender;

    /**
     * Mailer constructor.
     *
     * @param ProviderInterface $provider
     * @param RendererInterface $renderer
     * @param SenderInterface   $sender
     */
    public function __construct(ProviderInterface $provider, RendererInterface $renderer, SenderInterface $sender)
    {
        $this->provider = $provider;
        $this->renderer = $renderer;
        $this->sender = $sender;
    }

    /**
     * {@inheritdoc}
     */
    public function send(StringLiteral $code, array $recipients, array $data = [], array $attachments = [])
    {
        $email = $this->provider->getEmail($code);
        if (!$email->isEnabled()) {
            return;
        }

        $senderAddress = $email->senderAddress()->toNative();
        $senderName = $email->senderName()->toNative();

        $subject = $this->renderer->subject($email, $data);
        $body = $this->renderer->body($email, $data);

        $this->sender->send(
            $recipients,
            $senderAddress,
            $senderName,
            $subject,
            $body,
            $data,
            $attachments
        );

        DomainEventPublisher::publish(
            new EmailWasSent($code, $email->senderName(), $email->senderAddress(), $recipients)
        );
    }
}
