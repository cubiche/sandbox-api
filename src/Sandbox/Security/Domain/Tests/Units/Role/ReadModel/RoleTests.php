<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\Role\ReadModel;

use Cubiche\Domain\System\StringLiteral;
use Sandbox\Core\Domain\Tests\Units\ReadModel\ReadModelTestTrait;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;

/**
 * RoleTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RoleTests extends TestCase
{
    use ReadModelTestTrait;

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            RoleId::next(),
            StringLiteral::fromNative('ROLE_NAME'),
        ];
    }

    /**
     * Test setName method.
     */
    public function testSetName()
    {
        $this
            ->given($role = $this->createReadModel($this->getArguments()))
            ->then()
                ->string($role->name()->toNative())
                    ->isEqualTo('ROLE_NAME')
                ->and()
                ->when($role->setName(StringLiteral::fromNative('ANOTHER_NAME')))
                ->then()
                    ->string($role->name()->toNative())
                        ->isEqualTo('ANOTHER_NAME')
        ;
    }

    /**
     * Test add/remove permission methods.
     */
    public function testAddRemovePermissions()
    {
        $permissionOne = StringLiteral::fromNative('app.user.create');
        $permissionTwo = StringLiteral::fromNative('app.user.list');

        $this
            ->given($role = $this->createReadModel($this->getArguments()))
            ->then()
                ->integer(count($role->permissions()))
                    ->isEqualTo(0)
                ->and()
                    ->when($role->addPermission($permissionOne))
                    ->then()
                        ->integer(count($role->permissions()))
                            ->isEqualTo(1)
                        ->and()
                        ->when($role->removePermission($permissionTwo))
                        ->then()
                            ->integer(count($role->permissions()))
                                ->isEqualTo(1)
                            ->and()
                            ->when($role->removePermission($permissionOne))
                            ->then()
                                ->integer(count($role->permissions()))
                                    ->isEqualTo(0)
        ;
    }
}
