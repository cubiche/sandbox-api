<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Country\Command;

use Sandbox\Core\Domain\Tests\Units\Command\CommandTestTrait;
use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Tests\Units\TestCase;

/**
 * CreateCountryCommandTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateCountryCommandTests extends TestCase
{
    use CommandTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            CountryId::next()->toNative(),
            'ES',
            array('en_US' => 'Spain', 'es_ES' => 'España'),
            'en_US',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function validatorProvider()
    {
        return array(
            array(
                'assert' => false,
                'arguments' => array(
                    '123',
                    'ES',
                    array('en_US' => 'Spain', 'es_ES' => 'España'),
                    'en_US',
                ),
            ),
            array(
                'assert' => false,
                'arguments' => array(
                    CountryId::next()->toNative(),
                    'ES',
                    array('XA' => 'Spain', 'es_ES' => 'España'),
                    'en_US',
                ),
            ),
            array(
                'assert' => false,
                'arguments' => array(
                    CountryId::next()->toNative(),
                    'ES',
                    array('en_US' => 1, 'es_ES' => 'España'),
                    'en_US',
                ),
            ),
            array(
                'assert' => false,
                'arguments' => array(
                    CountryId::next()->toNative(),
                    'ES',
                    array('en_US' => 'Spain', 'es_ES' => 'España'),
                    'a',
                ),
            ),
        );
    }
}
