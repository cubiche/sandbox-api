<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Session;

use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage as BaseSessionStorage;

/**
 * MockFileSessionStorage class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MockFileSessionStorage extends BaseSessionStorage
{
    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        if ($this->id !== $id) {
            $this->id = $id;
        }
    }
}
