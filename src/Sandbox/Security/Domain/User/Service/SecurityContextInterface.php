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

use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Security\Domain\User\UserId;

/**
 * SecurityContextInterface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface SecurityContextInterface
{
    /**
     * @return string
     */
    public function getJWT();

    /**
     * @return UserId
     */
    public function userId();

    /**
     * @return EmailAddress
     */
    public function userEmail();

    /**
     * @return bool
     */
    public function isAuthenticated();
}
