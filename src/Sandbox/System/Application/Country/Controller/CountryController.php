<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Country\Controller;

use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\System\Domain\Country\Command\CreateCountryCommand;
use Sandbox\System\Domain\Country\CountryId;

/**
 * CountryController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryController extends CommandController
{
    /**
     * @param array $name
     *
     * @return string
     */
    public function createAction($code, $name, $defaultLocale)
    {
        $countryId = CountryId::next()->toNative();
        $this->commandBus()->dispatch(
            new CreateCountryCommand($countryId, $code, $name, $defaultLocale)
        );

        return $countryId;
    }
}
