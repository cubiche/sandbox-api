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
use Sandbox\Security\Domain\User\Event\UserVerificationWasRequested;
use Sandbox\Security\Domain\User\Event\UserWasCreated;
use Sandbox\Security\Domain\User\Event\UserWasVerified;
use Sandbox\System\Domain\Mailer\Command\SendEmailCommand;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\ProcessManager\ProcessManager;
use Cubiche\Domain\ProcessManager\ProcessManagerConfig;

/**
 * UserRegisterProcessManager class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserRegisterProcessManager extends ProcessManager implements DomainEventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    protected function build(ProcessManagerConfig $config)
    {
        $config
            ->addStates(
                array(
                    UserRegisterProcessStates::CREATED,
                    UserRegisterProcessStates::NOT_VERIFIED,
                    UserRegisterProcessStates::VERIFIED,
                )
            )
            ->addTransitions(
                array(
                    'verify' => array(
                        'from' => array(UserRegisterProcessStates::CREATED),
                        'to' => UserRegisterProcessStates::NOT_VERIFIED,
                    ),
                    'complete' => array(
                        'from' => array(UserRegisterProcessStates::NOT_VERIFIED),
                        'to' => UserRegisterProcessStates::VERIFIED,
                    ),
                )
            )
        ;
    }

    /**
     * @param UserWasCreated $event
     */
    public function whenUserWasCreated(UserWasCreated $event)
    {
        /*
         * Initial state: CREATED
         */
        $state = new UserRegisterProcessState($event->userId());
        $this->persist($state);
    }

    /**
     * @param UserVerificationWasRequested $event
     */
    public function whenUserVerificationWasRequested(UserVerificationWasRequested $event)
    {
        /** @var UserRegisterProcessState $state */
        $state = $this->load($event->userId());

        /*
         * Transition to state: NOT_VERIFIED
         */
        $this->apply('verify', $state);
        $this->persist($state);

        $this->dispatch(
            new SendEmailCommand(
                UserEmailType::USER_VERIFICATION_REQUEST,
                array($event->email()->toNative()),
                array(
                    'username' => $event->username()->toNative(),
                    'emailVerificationToken' => $event->emailVerificationToken()->toNative(),
                )
            )
        );
    }

    /**
     * @param UserWasVerified $event
     */
    public function whenUserWasVerified(UserWasVerified $event)
    {
        /** @var UserRegisterProcessState $state */
        $state = $this->load($event->userId());

        /*
         * Transition to state: VERIFIED
         */
        $this->apply('complete', $state);
        $this->persist($state);

        $this->dispatch(
            new SendEmailCommand(
                UserEmailType::USER_REGISTRATION_SUCCESS,
                array($event->email()->toNative()),
                array(
                    'username' => $event->username()->toNative(),
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function name()
    {
        return 'app.process_manager.user_register';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserWasCreated::class => array('whenUserWasCreated', 250),
            UserVerificationWasRequested::class => array('whenUserVerificationWasRequested', 250),
            UserWasVerified::class => array('whenUserWasVerified', 250),
        );
    }
}
