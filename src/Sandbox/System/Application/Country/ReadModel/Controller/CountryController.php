<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Country\ReadModel\Controller;

use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\System\Domain\Country\ReadModel\Country;
use Sandbox\System\Domain\Country\ReadModel\Query\FindAllCountries;
use Sandbox\System\Domain\Country\ReadModel\Query\FindOneCountryByCode;

/**
 * CountryController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryController extends QueryController
{
    /**
     * @return Country[]
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(
            new FindAllCountries()
        );
    }

    /**
     * @param string $countryCode
     *
     * @return Country
     */
    public function findOneByCodeAction($countryCode)
    {
        return $this->queryBus()->dispatch(
            new FindOneCountryByCode($countryCode)
        );
    }
}
