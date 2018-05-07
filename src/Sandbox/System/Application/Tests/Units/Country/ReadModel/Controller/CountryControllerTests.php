<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Tests\Units\Country\ReadModel\Controller;

use Sandbox\Core\Application\Tests\Units\SettingTokenContextTrait;
use Sandbox\System\Application\Country\ReadModel\Controller\CountryController;
use Sandbox\System\Application\Tests\Units\TestCase;
use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Country\ReadModel\Country;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryControllerTests extends TestCase
{
    use SettingTokenContextTrait;

    /**
     * @return CountryController
     */
    protected function createController()
    {
        return new CountryController($this->queryBus(), $this->getTokenContext());
    }

    /**
     * Test FindAllAction method.
     */
    public function testFindAllAction()
    {
        $this
            ->given($controller = $this->createController())
            ->and($repository = $this->queryRepository(Country::class))
            ->then()
                ->array(iterator_to_array($controller->findAllAction()))
                    ->isEmpty()
            ->and()
            ->when(
                $repository->persist(
                    new Country(
                        CountryId::next(),
                        StringLiteral::fromNative('ES'),
                        LocalizableString::fromArray(array('en_US' => 'Spain', 'es_ES' => 'España'))
                    )
                )
            )
            ->then()
            ->array(iterator_to_array($controller->findAllAction()))
                ->isNotEmpty()
                    ->hasSize(1)
        ;
    }

    /**
     * Test FindOneByCodeAction method.
     */
    public function testFindOneByCodeAction()
    {
        $this
            ->given($controller = $this->createController())
            ->and($repository = $this->queryRepository(Country::class))
            ->and($code = 'ES')
            ->then()
                ->variable($controller->findOneByCodeAction($code))
                    ->isNull()
            ->and()
            ->when(
                $repository->persist(
                    new Country(
                        CountryId::next(),
                        StringLiteral::fromNative($code),
                        LocalizableString::fromArray(array('en_US' => 'Spain', 'es_ES' => 'España'))
                    )
                )
            )
            ->then()
                ->object($controller->findOneByCodeAction($code))
                    ->isInstanceOf(Country::class)
        ;
    }
}
