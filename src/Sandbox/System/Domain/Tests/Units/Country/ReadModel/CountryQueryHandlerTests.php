<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Country\ReadModel;

use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Country\ReadModel\Country;
use Sandbox\System\Domain\Country\ReadModel\Query\FindAllCountries;
use Sandbox\System\Domain\Country\ReadModel\Query\FindOneCountryByCode;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryQueryHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryQueryHandlerTests extends TestCase
{
    protected function addCountryToRepository()
    {
        $repository = $this->queryRepository(Country::class);

        $repository->persist(
            new Country(
                CountryId::next(),
                StringLiteral::fromNative('ES'),
                LocalizableString::fromArray(array('en_US' => 'Spain', 'es_ES' => 'España'))
            )
        );
    }

    /**
     * Test FindAllCountries method.
     */
    public function testFindAllCountries()
    {
        $this
            ->given($query = new FindAllCountries())
            ->then()
                ->array(iterator_to_array($this->queryBus()->dispatch($query)))
                    ->isEmpty()
            ->and()
            ->when($this->addCountryToRepository())
            ->then()
                ->array(iterator_to_array($this->queryBus()->dispatch($query)))
                    ->isNotEmpty()
                    ->hasSize(1)
        ;
    }

    /**
     * Test FindOneCountryByCode method.
     */
    public function testFindOneCountryByCode()
    {
        $this
            ->given($query = new FindOneCountryByCode('ES'))
            ->then()
                ->variable($this->queryBus()->dispatch($query))
                    ->isNull()
            ->and()
            ->when($this->addCountryToRepository())
            ->then()
                ->object($this->queryBus()->dispatch($query))
                    ->isInstanceOf(Country::class)
        ;
    }
}
