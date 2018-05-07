<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country\ReadModel;

use Sandbox\System\Domain\Country\ReadModel\Query\FindAllCountries;
use Sandbox\System\Domain\Country\ReadModel\Query\FindOneCountryByCode;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryQueryHandler
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
     * @param FindAllCountries $query
     *
     * @return Country
     */
    public function findAllCountries(FindAllCountries $query)
    {
        return $this->repository->getIterator();
    }

    /**
     * @param FindOneCountryByCode $query
     *
     * @return Country
     */
    public function findOneCountryByCode(FindOneCountryByCode $query)
    {
        return $this->repository->findOne(
            Criteria::property('code')->eq(StringLiteral::fromNative($query->code()))
        );
    }
}
