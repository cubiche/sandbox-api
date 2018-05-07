<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Currency;

use Sandbox\System\Domain\Currency\Currency;
use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\CurrencyId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Locale\LocaleCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CurrencyTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyTests extends TestCase
{
    protected function createCurrency()
    {
        return new Currency(
            CurrencyId::next(),
            LocalizableString::fromArray(array('en_US' => 'Dollar', 'es_ES' => 'Dolar')),
            CurrencyCode::USD(),
            StringLiteral::fromNative('$')
        );
    }

    /**
     * Test Name method.
     */
    public function testName()
    {
        $this
            ->given($currency = $this->createCurrency())
            ->then()
                ->string($currency->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('Dolar')
        ;
    }

    /**
     * Test Code method.
     */
    public function testCode()
    {
        $this
            ->given($currency = $this->createCurrency())
            ->then()
                ->object($currency->code())
                    ->isEqualTo(CurrencyCode::USD())
        ;
    }

    /**
     * Test Symbol method.
     */
    public function testSymbol()
    {
        $this
            ->given($currency = $this->createCurrency())
            ->then()
                ->string($currency->symbol()->toNative())
                    ->isEqualTo('$')
        ;
    }

    /**
     * Test enable/disable method.
     */
    public function testEnableDisable()
    {
        $this
            ->given($currency = $this->createCurrency())
            ->then()
                ->boolean($currency->isEnabled())
                    ->isTrue()
                ->and()
                ->when($currency->disable())
                ->then()
                    ->boolean($currency->isEnabled())
                        ->isFalse()
                    ->and()
                    ->when($currency->enable())
                    ->then()
                        ->boolean($currency->isEnabled())
                            ->isTrue()
        ;
    }

    public function testUpdateName()
    {
        $this
            ->given($currency = $this->createCurrency())
            ->and($name = array('en_US' => 'Pound', 'es_ES' => 'Libra'))
            ->then()
                ->array($currency->name()->toArray())
                    ->isNotEqualTo($name)
            ->and()
            ->when($currency->updateName(LocalizableString::fromArray($name)))
            ->then()
                ->array($currency->name()->toArray())
                    ->isEqualTo($name)
        ;
    }
}
