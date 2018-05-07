<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Application\Service;

use Sandbox\Core\Domain\Exception\AccessDeniedException;
use Sandbox\Core\Application\Event\BeforeActionCallEvent;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;

/**
 * AccessControlMiddleware class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AccessControlMiddleware implements DomainEventSubscriberInterface
{
    /**
     * @var AccessControlCheckerInterface
     */
    protected $accessControlChecker;

    /**
     * AccessControlMiddleware constructor.
     *
     * @param AccessControlCheckerInterface $accessControlChecker
     */
    public function __construct(AccessControlCheckerInterface $accessControlChecker)
    {
        $this->accessControlChecker = $accessControlChecker;
    }

    /**
     * @param BeforeActionCallEvent $event
     *
     * @return mixed
     */
    public function beforeActionExecution(BeforeActionCallEvent $event)
    {
        if (!$this->accessControlChecker->isGranted($event->permissions())) {
            throw new AccessDeniedException(sprintf(
                "Access denied. You don't have permission to execute %s::%s using the credentials you supplied.",
                $event->method()->class,
                $event->method()->name
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BeforeActionCallEvent::class => array('beforeActionExecution', 250),
        );
    }
}
