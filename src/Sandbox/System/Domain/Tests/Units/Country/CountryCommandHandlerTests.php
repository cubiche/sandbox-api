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

use Sandbox\System\Domain\Country\Command\CreateCountryCommand;
use Sandbox\System\Domain\Country\Country;
use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Locale\LocaleCode;

/**
 * CountryCommandHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryCommandHandlerTests extends TestCase
{
    /**
     * Test CreateCountry method.
     */
    public function testCreateCountry()
    {
        $this
            ->given($countryId = CountryId::next())
            ->and(
                $command = new CreateCountryCommand(
                    $countryId->toNative(),
                    'ES',
                    array('en_US' => 'Spain', 'es_ES' => 'España'),
                    'en_US'
                )
            )
            ->when($repository = $this->writeRepository(Country::class))
            ->then()
                ->variable($repository->get($countryId))
                    ->isNull()
            ->and()
            ->when($this->commandBus()->dispatch($command))
            ->then()
                ->object($country = $repository->get($countryId))
                    ->isInstanceOf(Country::class)
                ->string($country->code()->toNative())
                    ->isEqualTo('ES')
                ->string($country->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('España')
        ;
    }
}
