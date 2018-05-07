<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Language;

use Sandbox\System\Domain\Language\Language;
use Sandbox\System\Domain\Language\LanguageId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Locale\LocaleCode;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * LanguageTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageTests extends TestCase
{
    /**
     * @return Language
     */
    protected function createLanguage()
    {
        return new Language(
            LanguageId::next(),
            LocalizableString::fromArray(array('en_US' => 'English', 'es_ES' => 'Ingles')),
            LanguageCode::EN()
        );
    }

    /**
     * Test Name method.
     */
    public function testName()
    {
        $this
            ->given($language = $this->createLanguage())
            ->then()
                ->string($language->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('Ingles')
        ;
    }

    /**
     * Test Code method.
     */
    public function testCode()
    {
        $this
            ->given($language = $this->createLanguage())
            ->then()
                ->object($language->code())
                    ->isEqualTo(LanguageCode::EN())
        ;
    }

    /**
     * Test enable/disable method.
     */
    public function testEnableDisable()
    {
        $this
            ->given($language = $this->createLanguage())
            ->then()
                ->boolean($language->isEnabled())
                    ->isTrue()
                ->and()
                ->when($language->disable())
                ->then()
                    ->boolean($language->isEnabled())
                        ->isFalse()
                    ->and()
                    ->when($language->enable())
                    ->then()
                        ->boolean($language->isEnabled())
                            ->isTrue()
        ;
    }

    public function testUpdateName()
    {
        $this
            ->given($language = $this->createLanguage())
            ->and($name = array('en_US' => 'Spanish', 'es_ES' => 'Español'))
            ->then()
                ->array($language->name()->toArray())
                    ->isNotEqualTo($name)
            ->and()
            ->when($language->updateName(LocalizableString::fromArray($name)))
            ->then()
                ->array($language->name()->toArray())
                    ->isEqualTo($name)
        ;
    }
}
