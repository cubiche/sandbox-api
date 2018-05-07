<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units;

use Cubiche\Core\Collections\ArrayCollection\SortedArraySet;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * SettingPermissionRepository trait..
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait SettingPermissionRepositoryTrait
{
    /**
     * @return QueryRepositoryInterface
     */
    protected function permissionRepository()
    {
        $permissions = new SortedArraySet(array(
            'app',
            'app.user',
            'app.user.create',
        ));

        $repositoryMock = $this->newMockInstance('Cubiche\Domain\Repository\QueryRepositoryInterface');
        $this->calling($repositoryMock)->getIterator = function () use ($permissions) {
            return $permissions->getIterator();
        };

        $this->calling($repositoryMock)->findOne = function (SpecificationInterface $criteria) use ($permissions) {
            return $permissions->findOne($criteria);
        };

        return $repositoryMock;
    }
}
