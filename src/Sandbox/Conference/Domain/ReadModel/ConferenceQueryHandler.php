<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Domain\ReadModel;

use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Conference\Domain\ReadModel\Query\FindAllConferences;
use Sandbox\Conference\Domain\ReadModel\Query\FindOneConferenceById;

/**
 * ConferenceQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * ConferenceQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindAllConferences $query
     *
     * @return Conference
     */
    public function findAllConferences(FindAllConferences $query)
    {
        return $this->repository->getIterator();
    }

    /**
     * @param FindOneConferenceById $query
     *
     * @return Conference
     */
    public function findOneConferenceById(FindOneConferenceById $query)
    {
        return $this->repository->findOne(
            Criteria::property('id')->eq(ConferenceId::fromNative($query->conferenceId()))
        );
    }
}
