<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\ReadModel;

use Sandbox\System\Domain\Currency\ReadModel\Query\FindAllCurrencies;
use Sandbox\System\Domain\Currency\ReadModel\Query\FindOneCurrencyByCode;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * CurrencyQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * CurrencyQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindAllCurrencies $query
     *
     * @return Currency
     */
    public function findAllCurrencies(FindAllCurrencies $query)
    {
        return $this->repository->getIterator();
    }

    /**
     * @param FindOneCurrencyByCode $query
     *
     * @return Currency
     */
    public function findOneCurrencyByCode(FindOneCurrencyByCode $query)
    {
        return $this->repository->findOne(
            Criteria::property('code')->eq(StringLiteral::fromNative($query->code()))
        );
    }
}
