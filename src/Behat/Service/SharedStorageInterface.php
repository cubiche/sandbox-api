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
interface SharedStorageInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * @param string $key
     * @param mixed  $resource
     */
    public function set($key, $resource);
}
