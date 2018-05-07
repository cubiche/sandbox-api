<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Currency\Service;

use Symfony\Component\Intl\Intl;

/**
 * CurrencyProvider class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyProvider
{
    /**
     * @param array $locales
     *
     * @return array
     */
    public function getAllCurrencies(array $locales = array())
    {
        $currencies = array();
        foreach ($locales as $locale) {
            $result = Intl::getCurrencyBundle()->getCurrencyNames($locale);
            foreach ($result as $currencyCode => $currencyName) {
                if (!isset($currencies[$currencyCode])) {
                    $currencies[$currencyCode] = array(
                        'symbol' => Intl::getCurrencyBundle()->getCurrencySymbol($currencyCode),
                        'names' => array(),
                    );
                }

                $currencies[$currencyCode]['names'][$locale] = ucwords($currencyName);
            }
        }

        return $currencies;
    }
}
