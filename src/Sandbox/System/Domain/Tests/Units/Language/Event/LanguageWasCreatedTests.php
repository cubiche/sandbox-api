<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Language\Event;

use Sandbox\Core\Domain\Tests\Units\Event\EventTestTrait;
use Sandbox\System\Domain\Language\LanguageId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * LanguageWasCreatedTests class.
 *
 * Generated by TestGenerator on 2018-01-25 at 17:26:00.
 */
class LanguageWasCreatedTests extends TestCase
{
    use EventTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            LanguageId::next(),
            LocalizableString::fromArray(array('en_US' => 'English', 'es_ES' => 'Ingles')),
            LanguageCode::EN(),
        );
    }
}