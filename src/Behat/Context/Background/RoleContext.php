<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Background;

use Behat\Behat\Context\Context;
use Behat\Service\SharedStorageInterface;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\RoleId;
use Cubiche\Core\Cqrs\Command\CommandBus;

/**
 * RoleContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class RoleContext implements Context
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var TokenContext
     */
    protected $tokenContext;

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * RoleContext constructor.
     *
     * @param CommandBus             $commandBus
     * @param TokenContext           $tokenContext
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        CommandBus $commandBus,
        TokenContext $tokenContext,
        SharedStorageInterface $sharedStorage
    ) {
        $this->commandBus = $commandBus;
        $this->tokenContext = $tokenContext;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given there is a role :name
     * @Given /^there is a role "([^"]+)" with the (permission(?:|s) "[^"]+")$/
     *
     * @param string $name
     * @param array  $permissions
     */
    public function thereIsARoleWithPermissions($name, array $permissions = array())
    {
        $this->createRole($name, $permissions);
    }

    /**
     * @Given there is an admin role
     */
    public function thereIsAnAdminRole()
    {
        $this->createRole('admin', array('app'));
    }

    /**
     * @param string $name
     * @param array  $permissions
     */
    private function createRole($name, array $permissions = array())
    {
        // there is a bug in the cross container service between the __symfony__ and the __symfony_shared__ containers
        // normally setting the token in the tokenContext under the __symfony_shared__ should works, but doesn't work
        // a workaround is to set directly here before the role creation the token in tokenContext
        // under the __symfony__ container
        $this->tokenContext->setTokenInContext();

        $roleId = RoleId::nextUUIDValue();

        $this->commandBus->dispatch(
            new CreateRoleCommand(
                $roleId,
                $name,
                $permissions
            )
        );

        $this->sharedStorage->set('role-'.$name, $roleId);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasRoleWith($name)
    {
        return $this->sharedStorage->has('role-'.$name);
    }

    /**
     * @return bool
     */
    public function hasAdminRole()
    {
        return $this->sharedStorage->has('role-admin') || $this->sharedStorage->has('role-ADMIN');
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getRoleWith($name)
    {
        return $this->sharedStorage->get('role-'.$name);
    }

    /**
     * @return string
     */
    public function getAdminRole()
    {
        if ($this->sharedStorage->has('role-admin')) {
            return $this->sharedStorage->get('role-admin');
        }

        return $this->sharedStorage->get('role-ADMIN');
    }
}
