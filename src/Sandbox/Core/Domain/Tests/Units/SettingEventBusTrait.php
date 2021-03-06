<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Domain\Tests\Units;

use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Infrastructure\EventBus\Factory\EventBusFactory;

/**
 * SettingEventBus trait.
 *
 * Generated by TestGenerator on 2017-09-13 at 12:50:27.
 */
trait SettingEventBusTrait
{
    use SettingEventDispatcherTrait;

    /**
     * @var EventBus
     */
    protected $eventBus;

    /**
     * @return EventBus
     */
    public function eventBus()
    {
        if ($this->eventBus === null) {
            $factory = new EventBusFactory($this->eventDispatcher());

            $this->eventBus = $factory->create();
        }

        return $this->eventBus;
    }
}
