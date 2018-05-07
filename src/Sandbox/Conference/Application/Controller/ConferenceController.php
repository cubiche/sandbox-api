<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Application\Controller;

use Sandbox\Conference\Domain\Command\CreateConferenceCommand;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Core\Application\Controller\CommandController;
use DateTime;

/**
 * ConferenceController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceController extends CommandController
{
    /**
     * @param array  $name
     * @param array  $city
     * @param string $countryCode
     * @param int    $availableTickets
     * @param float  $amount
     * @param string $currency
     * @param string $startAt
     * @param string $endAt
     * @param string $defaultLocale
     *
     * @return string
     */
    public function createAction(
        array $name,
        array $city,
        $countryCode,
        $availableTickets,
        $amount,
        $currency,
        $startAt,
        $endAt,
        $defaultLocale
    ) {
        $conferenceId = ConferenceId::next()->toNative();
        $this->commandBus()->dispatch(
            new CreateConferenceCommand(
                $conferenceId,
                $name,
                $city,
                $countryCode,
                $availableTickets,
                $amount,
                $currency,
                $startAt,
                $endAt,
                $defaultLocale
            )
        );

        return $conferenceId;
    }
}
