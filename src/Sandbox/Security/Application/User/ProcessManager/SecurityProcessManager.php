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

use Sandbox\Security\Application\User\Command\GenerateJWTCommand;
use Sandbox\Security\Domain\User\Event\UserHasLoggedIn;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\ProcessManager\ProcessManager;

/**
 * SecurityProcessManager class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SecurityProcessManager extends ProcessManager implements DomainEventSubscriberInterface
{
    /**
     * @param UserHasLoggedIn $event
     */
    public function whenUserHasLoggedIn(UserHasLoggedIn $event)
    {
        $this->dispatch(new GenerateJWTCommand($event->userId()->toNative()));
    }

    /**
     * {@inheritdoc}
     */
    protected function name()
    {
        return 'app.process_manager.security';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserHasLoggedIn::class => array('whenUserHasLoggedIn', 250),
        );
    }
}
