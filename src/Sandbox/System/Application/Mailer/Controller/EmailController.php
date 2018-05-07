<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Mailer\Controller;

use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\System\Domain\Mailer\Command\SendEmailCommand;

/**
 * EmailController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EmailController extends CommandController
{
    /**
     * @param string $code
     * @param array  $recipients
     * @param array  $data
     * @param array  $attachments
     *
     * @return bool
     */
    public function sendAction($code, array $recipients, array $data = [], array $attachments = [])
    {
        $this->commandBus()->dispatch(
            new SendEmailCommand($code, $recipients, $data, $attachments)
        );

        return true;
    }
}
