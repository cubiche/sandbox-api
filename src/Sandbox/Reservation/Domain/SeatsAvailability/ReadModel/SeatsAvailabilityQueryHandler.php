<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\SeatsAvailability\ReadModel;

use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\Query\FindOneSeatsAvailabilityById;

/**
 * SeatsAvailabilityQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * SeatsAvailabilityQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindOneSeatsAvailabilityById $query
     *
     * @return SeatsAvailability|null
     */
    public function findOneSeatsAvailabilityById(FindOneSeatsAvailabilityById $query)
    {
        return $this->repository->findOne(
            Criteria::property('id')->eq(ConferenceId::fromNative($query->conferenceId()))
        );
    }
}
