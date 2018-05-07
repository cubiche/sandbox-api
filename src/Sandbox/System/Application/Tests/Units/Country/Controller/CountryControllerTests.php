<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Tests\Units\Country\Controller;

use Sandbox\Core\Application\Tests\Units\SettingTokenContextTrait;
use Sandbox\System\Application\Country\Controller\CountryController;
use Sandbox\System\Application\Tests\Units\TestCase;
use Sandbox\System\Domain\Country\ReadModel\Country;
use Sandbox\System\Domain\Country\ReadModel\Query\FindAllCountries;

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
        return new CountryController($this->commandBus(), $this->getTokenContext());
    }

    /**
     * @return Country
     */
    protected function findAllCountries()
    {
        return $this->queryBus()->dispatch(new FindAllCountries());
    }

    /**
     * Test CreateAction method.
     */
    public function testCreateAction()
    {
        $this
            ->given($controller = $this->createController())
            ->and($name = array('en_US' => 'Spain', 'es_ES' => 'España'))
            ->when($country = $this->findAllCountries())
            ->then()
                ->integer($country->count())
                ->isEqualTo(0)
            ->and()
            ->when(
                $controller->createAction(
                    'ES',
                    $name,
                    'en_US'
                )
            )
            ->and($country = $this->findAllCountries())
            ->then()
                ->integer($country->count())
                    ->isEqualTo(1)
        ;
    }
}
