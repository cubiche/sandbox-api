<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Currency\Event;

use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\CurrencyId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CurrencyWasCreatedTests class.
 *
 * Generated by TestGenerator on 2018-01-25 at 17:26:31.
 */
class CurrencyWasCreatedTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            CurrencyId::next(),
            LocalizableString::fromArray(array('en_US' => 'Euro', 'es_ES' => 'Euro')),
            CurrencyCode::EUR(),
            StringLiteral::fromNative('€'),
        );
    }
}