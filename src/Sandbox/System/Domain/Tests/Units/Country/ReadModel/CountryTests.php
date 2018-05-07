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

use Sandbox\Core\Domain\Tests\Units\ReadModel\ReadModelTestTrait;
use Sandbox\System\Domain\Country\CountryId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryTests extends TestCase
{
    use ReadModelTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            CountryId::next(),
            StringLiteral::fromNative('ES'),
            LocalizableString::fromArray(array('en_US' => 'Spain', 'es_ES' => 'España')),
        );
    }
}
