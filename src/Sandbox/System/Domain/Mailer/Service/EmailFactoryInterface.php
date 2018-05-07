<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Mailer\Service;

use Sandbox\System\Domain\Mailer\Email;

/**
 * EmailFactory interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface EmailFactoryInterface
{
    /**
     * @param string $code
     * @param string $senderName
     * @param string $senderAddress
     * @param string $subject
     * @param string $content
     * @param string $template
     *
     * @return Email
     */
    public function create($code, $senderName, $senderAddress, $subject = null, $content = null, $template = null);
}
