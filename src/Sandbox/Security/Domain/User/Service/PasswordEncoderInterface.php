<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Service;

/**
 * Password encoder interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface PasswordEncoderInterface
{
    /**
     * @param string $plainPassword
     * @param string $salt
     *
     * @return string
     */
    public function encode($plainPassword, $salt);
}
