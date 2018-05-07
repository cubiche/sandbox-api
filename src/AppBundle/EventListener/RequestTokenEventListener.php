<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use Sandbox\Core\Infrastructure\Service\TokenContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * RequestTokenEventListener class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RequestTokenEventListener
{
    /**
     * @var TokenContext
     */
    protected $tokenContext;

    /**
     * RequestTokenEventListener constructor.
     *
     * @param TokenContext $tokenContext
     */
    public function __construct(TokenContext $tokenContext)
    {
        $this->tokenContext = $tokenContext;
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->tokenContext->setRequest($event->getRequest());
    }
}
