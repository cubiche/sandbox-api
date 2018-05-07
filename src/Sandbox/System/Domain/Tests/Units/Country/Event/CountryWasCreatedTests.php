<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Country\Event;

use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryWasCreatedTests class.
 *
 * Generated by TestGenerator on 2018-02-23 at 10:48:23.
 */
class CountryWasCreatedTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            CountryId::next(),
            StringLiteral::fromNative('ES'),
            LocalizableString::fromArray(array('en_US' => 'Euro', 'es_ES' => 'Euro')),
        );
    }
}
