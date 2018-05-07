<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Country\ReadModel\Projection;

use Sandbox\System\Domain\Country\Command\CreateCountryCommand;
use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Country\ReadModel\Country as ReadModelCountry;
use Sandbox\System\Domain\Country\ReadModel\Projection\CountryProjector;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\ReadModelInterface;

/**
 * CountryProjectorTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryProjectorTests extends TestCase
{
    /**
     * Test create method.
     */
    public function testCreate()
    {
        $this
            ->given(
                $projector = new CountryProjector(
                    $this->queryRepository(ReadModelCountry::class)
                )
            )
            ->then()
                ->array($projector->getSubscribedEvents())
                    ->isNotEmpty()
        ;
    }

    /**
     * Test WhenCountryWasCreated method.
     */
    public function testWhenCountryWasCreated()
    {
        $this
            ->given($repository = $this->queryRepository(ReadModelCountry::class))
            ->and(
                $countryId = CountryId::next(),
                $command = new CreateCountryCommand(
                    $countryId->toNative(),
                    'ES',
                    array('en_US' => 'Spain', 'es_ES' => 'España'),
                    'en_US'
                )
            )
            ->then()
                ->boolean($repository->isEmpty())
                    ->isTrue()
            ->and()
            ->when($this->commandBus()->dispatch($command))
            ->then()
                ->boolean($repository->isEmpty())
                    ->isFalse()
                ->object($repository->get($countryId))
                    ->isInstanceOf(ReadModelInterface::class)
        ;
    }
}
