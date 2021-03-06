<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Currency\Command;

use Sandbox\Core\Domain\Tests\Units\Command\CommandTestTrait;
use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\CurrencyId;
use Sandbox\System\Domain\Tests\Units\TestCase;

/**
 * CreateCurrencyCommandTests class.
 *
 * Generated by TestGenerator on 2018-01-25 at 17:26:31.
 */
class CreateCurrencyCommandTests extends TestCase
{
    use CommandTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            CurrencyId::next()->toNative(),
            CurrencyCode::EUR,
            '€',
            array('en_US' => 'Euro', 'es_ES' => 'Euro'),
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
                    CurrencyCode::EUR,
                    '€',
                    array('en_US' => 'Euro', 'es_ES' => 'Euro'),
                ),
            ),
            array(
                'assert' => false,
                'arguments' => array(
                    '123',
                    'foo',
                    '€',
                    array('en_US' => 'Euro', 'es_ES' => 'Euro'),
                ),
            ),
            array(
                'assert' => false,
                'arguments' => array(
                    CurrencyId::next()->toNative(),
                    CurrencyCode::EUR,
                    '€',
                    array('Euro'),
                ),
            ),
        );
    }
}
