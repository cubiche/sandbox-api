<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Permission\ReadModel\Controller;

use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\Security\Domain\Permission\ReadModel\Query\FindAllPermissions;
use Sandbox\Security\Domain\Permission\ReadModel\Query\FindOnePermissionByName;

/**
 * PermissionController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PermissionController extends QueryController
{
    /**
     * @return \Traversable
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(
            new FindAllPermissions()
        );
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function findOneByNameAction($name)
    {
        return $this->queryBus()->dispatch(
            new FindOnePermissionByName($name)
        );
    }
}
