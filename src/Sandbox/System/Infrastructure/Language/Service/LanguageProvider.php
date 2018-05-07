<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Language\Service;

use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Locale\LocaleCode;
use Symfony\Component\Intl\Intl;

/**
 * LanguageProvider class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageProvider
{
    /**
     * @var array
     */
    protected static $languageCodeMapping = [
        LocaleCode::EN_US => LanguageCode::EN_US,
        LocaleCode::FR_FR => LanguageCode::FR,
        LocaleCode::NL_NL => LanguageCode::NL,
    ];

    /**
     * @param string $localeCode
     *
     * @return string
     */
    private function localeToLanguageCode($localeCode)
    {
        $languageCode = $localeCode;
        if (isset(self::$languageCodeMapping[$localeCode])) {
            $languageCode = self::$languageCodeMapping[$localeCode];
        }

        return $languageCode;
    }

    /**
     * @param array $locales
     *
     * @return string
     */
    private function localesToLanguagesCode($locales)
    {
        $languagesCode = array();
        foreach ($locales as $locale) {
            $languagesCode[] = $this->localeToLanguageCode($locale);
        }

        return $languagesCode;
    }

    /**
     * @param array $locales
     *
     * @return array
     */
    public function getAllLanguages(array $locales = array())
    {
        $languages = array();
        foreach ($this->localesToLanguagesCode($locales) as $languageCode) {
            if (!isset($languages[$languageCode])) {
                $languages[$languageCode] = array(
                    'code' => $languageCode,
                    'names' => array(),
                );
            }

            foreach ($locales as $locale) {
                $name = ucwords(Intl::getLocaleBundle()->getLocaleName($languageCode, $locale));
                $languages[$languageCode]['names'][$locale] = $name;
            }
        }

        return $languages;
    }
}
