<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Country;

use Sandbox\System\Domain\Country\Country;
use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\Locale\LocaleCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(AggregateRootInterface::class)
        ;
    }

    /**
     * Test Name method.
     */
    public function testName()
    {
        $this
            ->given($country = new Country(
                CountryId::next(),
                StringLiteral::fromNative('ES'),
                LocalizableString::fromArray(array('en_US' => 'Spain', 'es_ES' => 'España'))
            ))
            ->then()
                ->string($country->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('España')
                ->string($country->code()->toNative())
                    ->isEqualTo('ES')
        ;
    }
}
