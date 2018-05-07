<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Application\Event;

use Cubiche\Domain\EventPublisher\DomainEvent;

/**
 * BeforeActionCallEvent class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class BeforeActionCallEvent extends DomainEvent
{
    /**
     * @var \ReflectionMethod
     */
    protected $method;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var array
     */
    protected $permissions;

    /**
     * BeforeActionCallEvent constructor.
     *
     * @param \ReflectionMethod $method
     * @param array             $arguments
     * @param array             $permissions
     */
    public function __construct(\ReflectionMethod $method, array $arguments, array $permissions)
    {
        $this->method = $method;
        $this->arguments = $arguments;
        $this->permissions = $permissions;
    }

    /**
     * @return \ReflectionMethod
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function arguments()
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function permissions()
    {
        return $this->permissions;
    }
}
