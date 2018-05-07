<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Domain\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;
use Cubiche\Domain\Localizable\LocalizableValueInterface;

/**
 * CreateConferenceCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateConferenceCommand extends Command
{
    /**
     * @var string
     */
    protected $conferenceId;

    /**
     * @var array
     */
    protected $name;

    /**
     * @var array
     */
    protected $city;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var int
     */
    protected $availableTickets;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var DateTime
     */
    protected $startAt;

    /**
     * @var DateTime
     */
    protected $endAt;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * CreateConferenceCommand constructor.
     *
     * @param string $conferenceId
     * @param array  $name
     * @param array  $city
     * @param string $countryCode
     * @param int    $availableTickets
     * @param float  $amount
     * @param string $currency
     * @param string $startAt
     * @param string $endAt
     * @param string $defaultLocale
     */
    public function __construct(
        $conferenceId,
        array $name,
        array $city,
        $countryCode,
        $availableTickets,
        $amount,
        $currency,
        $startAt,
        $endAt,
        $defaultLocale = LocalizableValueInterface::DEFAULT_LOCALE
    ) {
        $this->conferenceId = $conferenceId;
        $this->name = $name;
        $this->city = $city;
        $this->countryCode = $countryCode;
        $this->availableTickets = $availableTickets;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return array
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function countryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return int
     */
    public function availableTickets()
    {
        return $this->availableTickets;
    }

    /**
     * @return float
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function currency()
    {
        return $this->currency;
    }

    /**
     * @return DateTime
     */
    public function startAt()
    {
        return $this->startAt;
    }

    /**
     * @return DateTime
     */
    public function endAt()
    {
        return $this->endAt;
    }

    /**
     * @return string
     */
    public function defaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('conferenceId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('countryCode', Assertion::countryCode()->notBlank());
        $classMetadata->addPropertyConstraint('availableTickets', Assertion::integer()->notBlank());
        $classMetadata->addPropertyConstraint('amount', Assertion::float()->notBlank());
        $classMetadata->addPropertyConstraint('currency', Assertion::string()->notBlank());
        $classMetadata->addPropertyConstraint('startAt', Assertion::date('Y-m-d')->notBlank());
        $classMetadata->addPropertyConstraint('endAt', Assertion::date('Y-m-d')->notBlank());
        $classMetadata->addPropertyConstraint('defaultLocale', Assertion::localeCode()->notBlank());

        $classMetadata->addPropertyConstraint(
            'name',
            Assertion::isArray()->each(
                Assertion::localeCode()->notBlank(),
                Assertion::string()->notBlank()
            )
        );

        $classMetadata->addPropertyConstraint(
            'city',
            Assertion::isArray()->each(
                Assertion::localeCode()->notBlank(),
                Assertion::string()->notBlank()
            )
        );
    }
}
