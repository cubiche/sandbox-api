<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Mailer;

use Sandbox\System\Domain\Mailer\Email;
use Sandbox\System\Domain\Mailer\Service\Mailer;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * SettingMailer trait..
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait SettingMailerTrait
{
    /**
     * @param bool $enabled
     *
     * @return Mailer
     */
    protected function mailer($enabled = true)
    {
        $providerMock = $this->newMockInstance('Sandbox\System\Domain\Mailer\Service\ProviderInterface');
        $this->calling($providerMock)->getEmail = function (StringLiteral $code) use ($enabled) {
            $email = new Email(
                $code,
                StringLiteral::fromNative('Company Name'),
                EmailAddress::fromNative('test@example.com'),
                StringLiteral::fromNative('Reset password'),
                StringLiteral::fromNative('Just click the button below to finish resetting your password.'),
                StringLiteral::fromNative('Mailer/User/resetPassword.html.twig')
            );

            if (!$enabled) {
                $email->disable();
            }

            return $email;
        };

        $rendererMock = $this->newMockInstance('Sandbox\System\Domain\Mailer\Service\RendererInterface');
        $this->calling($rendererMock)->subject = function (Email $email, array $data = []) {
            return $email->subject()->toNative();
        };

        $this->calling($rendererMock)->body = function (Email $email, array $data = []) {
            return $email->content()->toNative();
        };

        $senderMock = $this->newMockInstance('Sandbox\System\Domain\Mailer\Service\SenderInterface');
        $this->calling($senderMock)->send = function (
            array $recipients,
            $senderAddress,
            $senderName,
            $subject,
            $body,
            array $data,
            array $attachments = []
        ) {
            return true;
        };

        return new Mailer($providerMock, $rendererMock, $senderMock);
    }
}
