<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Currency\ReadModel;

use Sandbox\Core\Domain\Tests\Units\ReadModel\ReadModelTestTrait;
use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\CurrencyId;
use Sandbox\System\Domain\Currency\ReadModel\Currency;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Locale\LocaleCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CurrencyTests class.
 *
 * Generated by TestGenerator on 2018-01-25 at 17:26:31.
 */
class CurrencyTests extends TestCase
{
    use ReadModelTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            CurrencyId::next(),
            LocalizableString::fromArray(array('en_US' => 'Dollar', 'es_ES' => 'Dolar')),
            StringLiteral::fromNative(CurrencyCode::USD),
            StringLiteral::fromNative('$'),
        );
    }

    /**
     * Test setName method.
     */
    public function testSetName()
    {
        $this
            ->given($currency = $this->createReadModel($this->getArguments()))
            ->then()
                ->string($currency->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('Dolar')
                ->and()
                ->when(
                    $currency->setName(
                        LocalizableString::fromArray(array('en_US' => 'English', 'es_ES' => 'US Dolar'))
                    )
                )
                ->then()
                    ->string($currency->name()->translate(LocaleCode::ES_ES()))
                        ->isEqualTo('US Dolar')
        ;
    }

    /**
     * Test setCode method.
     */
    public function testSetCode()
    {
        $this
            ->given($currency = $this->createReadModel($this->getArguments()))
            ->then()
                ->object($currency->code())
                    ->isEqualTo(StringLiteral::fromNative(CurrencyCode::USD))
                ->and()
                ->when($currency->setCode(StringLiteral::fromNative(CurrencyCode::EUR)))
                ->then()
                    ->object($currency->code())
                        ->isEqualTo(StringLiteral::fromNative(CurrencyCode::EUR))
        ;
    }

    /**
     * Test SetSymbol method.
     */
    public function testSetSymbol()
    {
        $this
            ->given($currency = $this->createReadModel($this->getArguments()))
            ->then()
                ->string($currency->symbol()->toNative())
                    ->isEqualTo('$')
                ->and()
                ->when($currency->setSymbol(StringLiteral::fromNative('€')))
                ->then()
                    ->string($currency->symbol()->toNative())
                        ->isEqualTo('€')
        ;
    }

    /**
     * Test enable/disable method.
     */
    public function testIsEnabled()
    {
        /* @var Currency $currency */
        $this
            ->given($currency = $this->createReadModel($this->getArguments()))
            ->then()
                ->boolean($currency->isEnabled())
                    ->isEqualTo(true)
            ->and()
            ->when($currency->disable())
            ->then()
                ->boolean($currency->isEnabled())
                    ->isEqualTo(false)
            ->and()
            ->when($currency->enable())
            ->then()
                ->boolean($currency->isEnabled())
                    ->isEqualTo(true)
        ;
    }
}
