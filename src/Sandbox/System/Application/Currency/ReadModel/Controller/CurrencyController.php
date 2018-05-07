<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Currency\ReadModel\Controller;

use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\System\Domain\Currency\ReadModel\Currency;
use Sandbox\System\Domain\Currency\ReadModel\Query\FindAllCurrencies;
use Sandbox\System\Domain\Currency\ReadModel\Query\FindOneCurrencyByCode;

/**
 * CurrencyController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyController extends QueryController
{
    /**
     * @return Currency[]
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(
            new FindAllCurrencies()
        );
    }

    /**
     * @param string $code
     *
     * @return Currency
     */
    public function findOneByCodeAction($code)
    {
        return $this->queryBus()->dispatch(
            new FindOneCurrencyByCode($code)
        );
    }
}
