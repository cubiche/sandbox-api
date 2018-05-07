<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Currency\Controller;

use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\System\Domain\Currency\Command\CreateCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\DisableCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\EnableCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\UpdateCurrencyNameCommand;
use Sandbox\System\Domain\Currency\CurrencyId;

/**
 * CurrencyController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyController extends CommandController
{
    /**
     * @param string $code
     * @param string $symbol
     * @param array  $name
     * @param string $defaultLocale
     *
     * @return string
     */
    public function createAction($code, $symbol, array $name, $defaultLocale)
    {
        $currencyId = CurrencyId::next()->toNative();
        $this->commandBus()->dispatch(
            new CreateCurrencyCommand($currencyId, $code, $symbol, $name, $defaultLocale)
        );

        return $currencyId;
    }

    /**
     * @param string $currencyId
     * @param array  $name
     *
     * @return bool
     */
    public function updateNameAction($currencyId, array $name)
    {
        $this->commandBus()->dispatch(
            new UpdateCurrencyNameCommand($currencyId, $name)
        );

        return true;
    }

    /**
     * @param string $currencyId
     *
     * @return bool
     */
    public function disableAction($currencyId)
    {
        $this->commandBus()->dispatch(
            new DisableCurrencyCommand($currencyId)
        );

        return true;
    }

    /**
     * @param string $currencyId
     *
     * @return bool
     */
    public function enableAction($currencyId)
    {
        $this->commandBus()->dispatch(
            new EnableCurrencyCommand($currencyId)
        );

        return true;
    }
}
