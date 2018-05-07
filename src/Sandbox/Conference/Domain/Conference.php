<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Domain;

use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\Locale\CountryCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\DateTime\DateRange;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Conference\Domain\Event\ConferenceWasCreated;
use Sandbox\System\Domain\Currency\Money;

/**
 * Conference class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Conference extends AggregateRoot
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
     * Conference constructor.
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

        $this->recordAndApplyEvent(
            new ConferenceWasCreated($conferenceId, $name, $city, $countryCode, $availableTickets, $price, $date)
        );
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->id;
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return StringLiteral
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

    /**
     * @param ConferenceWasCreated $event
     */
    protected function applyConferenceWasCreated(ConferenceWasCreated $event)
    {
        $this->name = $event->name();
        $this->city = $event->city();
        $this->countryCode = $event->countryCode();
        $this->availableTickets = $event->availableTickets();
        $this->price = $event->price();
        $this->date = $event->date();
    }
}
