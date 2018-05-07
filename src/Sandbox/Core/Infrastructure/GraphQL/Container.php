<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Infrastructure\GraphQL;

use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainerInterface;
use Youshido\GraphQL\Execution\Container\ContainerInterface;

/**
 * Container class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var SymfonyContainerInterface
     */
    protected $container;

    /**
     * Container constructor.
     *
     * @param SymfonyContainerInterface $container
     */
    public function __construct(SymfonyContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $serviceId #Service
     *
     * @return mixed
     */
    public function get($serviceId)
    {
        return $this->container->get($serviceId);
    }

    /**
     * @param string $serviceId
     * @param mixed  $value
     *
     * @return mixed
     */
    public function set($serviceId, $value)
    {
        return $this->container->set($serviceId, $value);
    }

    /**
     * @param string $serviceId
     *
     * @return mixed
     */
    public function remove($serviceId)
    {
        return;
    }

    /**
     * @param string $serviceId
     *
     * @return mixed
     */
    public function has($serviceId)
    {
        return $this->container->has($serviceId);
    }
}
