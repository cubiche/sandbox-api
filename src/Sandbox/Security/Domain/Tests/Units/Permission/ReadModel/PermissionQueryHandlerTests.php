<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Permission\ReadModel;

use Sandbox\Security\Domain\Permission\ReadModel\Query\FindAllPermissions;
use Sandbox\Security\Domain\Permission\ReadModel\Query\FindOnePermissionByName;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * PermissionQueryHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PermissionQueryHandlerTests extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function testFindAllPermissions()
    {
        $this
            ->given($query = new FindAllPermissions())
            ->then()
                ->array(iterator_to_array($this->queryBus()->dispatch($query)))
                    ->isNotEmpty()
                    ->hasSize(3)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testFindOnePermissionByName()
    {
        $this
            ->given($query = new FindOnePermissionByName('PERMISSION_SET::PERMISSION_ONE'))
            ->then()
                ->variable($this->queryBus()->dispatch($query))
                    ->isNull()
                ->string($this->queryBus()->dispatch(new FindOnePermissionByName('app.user.create')))
                    ->isEqualTo('app.user.create')
        ;
    }
}
