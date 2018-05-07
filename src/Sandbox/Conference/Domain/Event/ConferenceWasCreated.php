<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Domain\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\Locale\CountryCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\DateTime\DateRange;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\System\Domain\Currency\Money;

/**
 * ConferenceWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceWasCreated extends DomainEvent
{
    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * @var LocalizableString
     */
    protected $city;

    /**
     * @var CountryCode
     */
    protected $countryCode;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $availableTickets;

    /**
     * @var Money
     */
    protected $price;

    /**
     * @var DateRange
     */
    protected $date;

    /**
     * ConferenceWasCreated constructor.
     *
     * @param ConferenceId      $conferenceId
     * @param LocalizableString $name
     * @param LocalizableString $city
     * @param CountryCode       $countryCode
     * @param Integer           $availableTickets
     * @param Money             $price
     * @param DateRange         $date
     */
    public function __construct(
        ConferenceId $conferenceId,
        LocalizableString $name,
        LocalizableString $city,
        CountryCode $countryCode,
        Integer $availableTickets,
        Money $price,
        DateRange $date
    ) {
        parent::__construct($conferenceId);

        $this->name = $name;
        $this->city = $city;
        $this->countryCode = $countryCode;
        $this->availableTickets = $availableTickets;
        $this->price = $price;
        $this->date = $date;
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->aggregateId();
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return LocalizableString
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @return CountryCode
     */
    public function countryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function availableTickets()
    {
        return $this->availableTickets;
    }

    /**
     * @return Money
     */
    public function price()
    {
        return $this->price;
    }

    /**
     * @return DateRange
     */
    public function date()
    {
        return $this->date;
    }
}
