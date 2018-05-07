<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service;

/**
 * SharedStorage class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SharedStorage implements SharedStorageInterface
{
    /**
     * @var array
     */
    private $clipboard = [];

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (!isset($this->clipboard[$key])) {
            throw new \InvalidArgumentException(sprintf('There is no current resource for "%s"!', $key));
        }

        return $this->clipboard[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return isset($this->clipboard[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $resource)
    {
        $this->clipboard[$key] = $resource;
    }
}
