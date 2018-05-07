<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Tests\Units\Permission\ReadModel\Controller;

use Sandbox\Core\Application\Tests\Units\SettingTokenContextTrait;
use Sandbox\Security\Application\Permission\ReadModel\Controller\PermissionController;
use Sandbox\Security\Application\Tests\Units\TestCase;

/**
 * PermissionControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PermissionControllerTests extends TestCase
{
    use SettingTokenContextTrait;

    /**
     * @return PermissionController
     */
    public function createController()
    {
        return new PermissionController($this->queryBus());
    }

    /**
     * {@inheritdoc}
     */
    public function testFindAllAction()
    {
        $this
            ->given($controller = $this->createController())
            ->then()
            ->dump(iterator_to_array($controller->findAllAction()))
                ->array(iterator_to_array($controller->findAllAction()))
                    ->isNotEmpty()
                    ->hasSize(3)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testFindOneByNameAction()
    {
        $this
            ->given($controller = $this->createController())
            ->then()
            ->variable($controller->findOneByNameAction('notfound'))
                ->isNull()
            ->variable($controller->findOneByNameAction('app'))
                ->isNotNull()
        ;
    }
}
