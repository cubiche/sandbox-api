<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Permission\ReadModel;

use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Sandbox\Security\Domain\Permission\ReadModel\Query\FindAllPermissions;
use Sandbox\Security\Domain\Permission\ReadModel\Query\FindOnePermissionByName;

/**
 * PermissionQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PermissionQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * PermissionQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindAllPermissions $query
     *
     * @return string[]
     */
    public function findAllPermissions(FindAllPermissions $query)
    {
        return $this->repository->getIterator();
    }

    /**
     * @param FindOnePermissionByName $query
     *
     * @return string
     */
    public function findOnePermissionByName(FindOnePermissionByName $query)
    {
        return $this->repository->findOne(
            Criteria::this()->eq($query->name())
        );
    }
}
