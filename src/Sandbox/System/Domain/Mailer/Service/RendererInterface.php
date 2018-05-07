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
 * Renderer interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface RendererInterface
{
    /**
     * @param Email $email
     * @param array $data
     *
     * @return string
     */
    public function subject(Email $email, array $data = []);

    /**
     * @param Email $email
     * @param array $data
     *
     * @return string
     */
    public function body(Email $email, array $data = []);
}
