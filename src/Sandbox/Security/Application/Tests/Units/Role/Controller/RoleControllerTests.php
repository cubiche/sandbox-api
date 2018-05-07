<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Tests\Units\Role\Controller;

use Cubiche\Core\Validator\Exception\ValidationException;
use Sandbox\Core\Application\Tests\Units\SettingTokenContextTrait;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Application\Role\Controller\RoleController as WriteModelController;
use Sandbox\Security\Application\Role\ReadModel\Controller\RoleController as ReadModelController;
use Sandbox\Security\Application\Tests\Units\QueryControllerTestCase;

/**
 * RoleControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleControllerTests extends QueryControllerTestCase
{
    use SettingTokenContextTrait;

    /**
     * @return ReadModelController
     */
    protected function createController()
    {
        return $this->createReadModelController();
    }

    /**
     * @return WriteModelController
     */
    protected function createWriteModelController()
    {
        return new WriteModelController($this->commandBus());
    }

    /**
     * @return ReadModelController
     */
    protected function createReadModelController()
    {
        return new ReadModelController($this->queryBus());
    }

    /**
     * {@inheritdoc}
     */
    public function testRoleShouldBeCreated()
    {
        $this
            ->given($controller = $this->createWriteModelController())
            ->and($readModelController = $this->createReadModelController())
            ->and($tokenContext = $this->getTokenContext())
            ->when($roleId = $controller->createAction('admin'))
            ->then()
                ->variable($roleId)
                    ->isNotNull()
                ->variable($readModel = $readModelController->findOneByIdAction($roleId))
                    ->isNotNull()
                ->string($readModel->name()->toNative())
                    ->isEqualTo('ADMIN')
                ->object($roles = $readModelController->findAllAction())
                    ->hasSize(1)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testRoleShouldFailWithInvalidName()
    {
        $this
            ->given($controller = $this->createWriteModelController())
            ->and($readModelController = $this->createReadModelController())
            ->and($name = '')
            ->then()
                ->exception(function () use ($controller, $name) {
                    $controller->createAction($name);
                })->isInstanceOf(ValidationException::class)
                ->array($this->exception->getErrorExceptions())
                    ->isNotEmpty()
                ->and()
                ->object($roles = $readModelController->findAllAction())
                    ->hasSize(0)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testAddRemovePermissionAction()
    {
        $this
            ->given($controller = $this->createWriteModelController())
            ->and($permission = 'app.user.create')
            ->when($roleId = $controller->createAction('A ROLE'))
            ->then()
                ->variable($role = $this->createReadModelController()->findOneByIdAction($roleId))
                    ->isNotNull()
                ->integer($role->permissions()->count())
                    ->isEqualTo(0)
                ->exception(function () use ($controller, $roleId) {
                    $controller->addPermissionAction($roleId, 'app.user.list');
                })->isInstanceOf(NotFoundException::class)
                ->and()
                ->and($controller->addPermissionAction($roleId, $permission))
                ->and($role = $this->createReadModelController()->findOneByIdAction($roleId))
                ->then()
                    ->array($role->permissions()->toArray())
                        ->hasSize(1)
                        ->contains('app.user.create')
                    ->and()
                    ->when($controller->removePermissionAction($roleId, $permission))
                    ->and($role = $this->createReadModelController()->findOneByIdAction($roleId))
                    ->then()
                        ->integer($role->permissions()->count())
                            ->isEqualTo(0)
        ;
    }
}
