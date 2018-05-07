<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country\Event;

use Sandbox\System\Domain\Country\CountryId;
use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryWasCreated extends DomainEvent
{
    /**
     * @var StringLiteral
     */
    protected $code;

    /**
     * @var LocalizableString;
     */
    protected $name;

    public function __construct(CountryId $countryId, StringLiteral $code, LocalizableString $name)
    {
        parent::__construct($countryId);
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return CountryId
     */
    public function countryId()
    {
        return $this->aggregateId();
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
}
