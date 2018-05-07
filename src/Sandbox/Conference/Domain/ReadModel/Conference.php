<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source availableTickets.
 */

namespace Sandbox\Conference\Domain\ReadModel;

use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\DateTime\DateRange;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\System\Domain\Currency\Money;

/**
 * Conference class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Conference extends Entity implements ReadModelInterface
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
     * @var LocalizableString
     */
    protected $country;

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
     * @param LocalizableString $country
     * @param Integer           $availableTickets
     * @param Money             $price
     * @param DateRange         $date
     */
    public function __construct(
        ConferenceId $conferenceId,
        LocalizableString $name,
        LocalizableString $city,
        LocalizableString $country,
        Integer $availableTickets,
        Money $price,
        DateRange $date
    ) {
        parent::__construct($conferenceId);

        $this->name = $name;
        $this->city = $city;
        $this->country = $country;
        $this->availableTickets = $availableTickets;
        $this->price = $price;
        $this->date = $date;
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
     * @return LocalizableString
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @return LocalizableString
     */
    public function country()
    {
        return $this->country;
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
