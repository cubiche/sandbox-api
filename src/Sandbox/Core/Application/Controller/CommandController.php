<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Application\Controller;

use Cubiche\Core\Cqrs\Command\CommandBus;

/**
 * CommandController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class CommandController
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * CommandController constructor.
     *
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @return CommandBus
     */
    public function commandBus()
    {
        return $this->commandBus;
    }
}
