<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country;

use Sandbox\System\Domain\Country\Event\CountryWasCreated;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * Country class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Country extends AggregateRoot
{
    /**
     * @var StringLiteral
     */
    private $code;

    /**
     * @var LocalizableString
     */
    private $name;

    public function __construct(CountryId $countryId, StringLiteral $code, LocalizableString $name)
    {
        parent::__construct($countryId);

        $this->recordAndApplyEvent(
            new CountryWasCreated($countryId, $code, $name)
        );
    }

    /**
     * @return StringLiteral
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param CountryWasCreated $event
     */
    protected function applyCountryWasCreated(CountryWasCreated $event)
    {
        $this->code = $event->code();
        $this->name = $event->name();
    }
}
