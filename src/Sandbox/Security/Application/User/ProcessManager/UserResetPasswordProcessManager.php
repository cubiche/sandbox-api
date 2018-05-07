<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\User\ProcessManager;

use Sandbox\Security\Application\User\UserEmailType;
use Sandbox\Security\Domain\User\Event\UserResetPasswordWasRequested;
use Sandbox\System\Domain\Mailer\Command\SendEmailCommand;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\ProcessManager\ProcessManager;

/**
 * UserResetPasswordProcessManager class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserResetPasswordProcessManager extends ProcessManager implements DomainEventSubscriberInterface
{
    /**
     * @param UserResetPasswordWasRequested $event
     */
    public function whenUserResetPasswordWasRequested(UserResetPasswordWasRequested $event)
    {
        $this->dispatch(
            new SendEmailCommand(
                UserEmailType::USER_RESET_PASSWORD_REQUEST,
                array($event->email()->toNative()),
                array(
                    'username' => $event->username()->toNative(),
                    'passwordResetToken' => $event->passwordResetToken()->toNative(),
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function name()
    {
        return 'app.process_manager.user_reset_password';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserResetPasswordWasRequested::class => array('whenUserResetPasswordWasRequested', 250),
        );
    }
}
