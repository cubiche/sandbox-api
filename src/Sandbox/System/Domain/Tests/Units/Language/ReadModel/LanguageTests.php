<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Language\ReadModel;

use Sandbox\Core\Domain\Tests\Units\ReadModel\ReadModelTestTrait;
use Sandbox\System\Domain\Language\LanguageId;
use Sandbox\System\Domain\Language\ReadModel\Language;
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
    use ReadModelTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            LanguageId::next(),
            LocalizableString::fromArray(array('en_US' => 'English', 'es_ES' => 'Ingles')),
            LanguageCode::EN(),
        );
    }

    /**
     * Test setName method.
     */
    public function testSetName()
    {
        /* @var Language $language */
        $this
            ->given($language = $this->createReadModel($this->getArguments()))
            ->then()
                ->string($language->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('Ingles')
            ->and()
            ->when(
                $language->setName(
                    LocalizableString::fromArray(array('en_US' => 'English', 'es_ES' => 'Ingles UK'))
                )
            )
            ->then()
                ->string($language->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('Ingles UK')
        ;
    }

    /**
     * Test setCode method.
     */
    public function testSetCode()
    {
        /* @var Language $language */
        $this
            ->given($language = $this->createReadModel($this->getArguments()))
            ->then()
                ->object($language->code())
                    ->isEqualTo(LanguageCode::EN())
            ->and()
            ->when($language->setCode(LanguageCode::FR()))
            ->then()
                ->object($language->code())
                    ->isEqualTo(LanguageCode::FR())
        ;
    }

    /**
     * Test enable/disable method.
     */
    public function testIsEnabled()
    {
        /* @var Language $language */
        $this
            ->given($language = $this->createReadModel($this->getArguments()))
            ->then()
                ->boolean($language->isEnabled())
                    ->isEqualTo(true)
            ->and()
            ->when($language->disable())
            ->then()
                ->boolean($language->isEnabled())
                    ->isEqualTo(false)
            ->and()
            ->when($language->enable())
            ->then()
                ->boolean($language->isEnabled())
                    ->isEqualTo(true)
        ;
    }
}
