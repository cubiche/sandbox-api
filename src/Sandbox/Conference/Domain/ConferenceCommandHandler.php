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

use Cubiche\Domain\Locale\CountryCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\DateTime\Date;
use Cubiche\Domain\System\DateTime\DateRange;
use Cubiche\Domain\System\Integer;
use Sandbox\Conference\Domain\Command\CreateConferenceCommand;
use Sandbox\System\Domain\Currency\Money;

/**
 * ConferenceCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * ConferenceCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateConferenceCommand $command
     */
    public function createConference(CreateConferenceCommand $command)
    {
        $conference = new Conference(
            ConferenceId::fromNative($command->conferenceId()),
            LocalizableString::fromArray($command->name(), $command->defaultLocale()),
            LocalizableString::fromArray($command->city(), $command->defaultLocale()),
            CountryCode::fromNative($command->countryCode()),
            Integer::fromNative($command->availableTickets()),
            Money::fromNative($command->amount(), $command->currency()),
            new DateRange(
                Date::fromNative(\DateTime::createFromFormat('Y-m-d', $command->startAt())),
                Date::fromNative(\DateTime::createFromFormat('Y-m-d', $command->endAt()))
            )
        );

        $this->repository->persist($conference);
    }
}
