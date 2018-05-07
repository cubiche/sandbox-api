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
use Cubiche\Domain\System\StringLiteral;

/**
 * ProviderInterface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface ProviderInterface
{
    /**
     * @param StringLiteral $code
     *
     * @return Email
     */
    public function getEmail(StringLiteral $code);
}
