<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role\ReadModel;

use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindAllRoles;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleById;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleByName;
use Sandbox\Security\Domain\Role\RoleId;

/**
 * RoleQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $queryRepository;

    /**
     * RoleQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $queryRepository
     */
    public function __construct(QueryRepositoryInterface $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    /**
     * @param FindAllRoles $query
     *
     * @return Role[]
     */
    public function findAllRoles(FindAllRoles $query)
    {
        return $this->queryRepository->getIterator();
    }

    /**
     * @param FindOneRoleById $query
     *
     * @return Role
     */
    public function findOneRoleById(FindOneRoleById $query)
    {
        return $this->queryRepository->findOne(
            Criteria::property('id')->eq(RoleId::fromNative($query->roleId()))
        );
    }

    /**
     * @param FindOneRoleByName $query
     *
     * @return Role
     */
    public function findOneRoleByName(FindOneRoleByName $query)
    {
        return $this->queryRepository->findOne(
            Criteria::property('name')->eq(StringLiteral::fromNative($query->name()))
        );
    }
}
