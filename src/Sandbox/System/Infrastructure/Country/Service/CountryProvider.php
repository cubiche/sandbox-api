<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Country\Service;

use Symfony\Component\Intl\Intl;

/**
 * CountryProvider class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryProvider
{
    /**
     * @param array $locales
     *
     * @return array
     */
    public function getAllCountries(array $locales = array())
    {
        $countries = array();
        foreach ($locales as $locale) {
            $result = Intl::getRegionBundle()->getCountryNames($locale);
            foreach ($result as $countryCode => $countryName) {
                if (!isset($countries[$countryCode])) {
                    $countries[$countryCode] = array(
                        'names' => array(),
                    );
                }

                $countries[$countryCode]['names'][$locale] = ucwords($countryName);
            }
        }

        return $countries;
    }
}
