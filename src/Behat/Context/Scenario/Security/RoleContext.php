<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Scenario\Security;

use Behat\Behat\Context\Context;
use Behat\Service\Role\Mutation\AddPermissionsToRoleService;
use Behat\Service\Role\Mutation\CreateRoleService;
use Behat\Service\Role\Mutation\RemovePermissionsFromRoleService;
use Behat\Service\Role\Query\FindAllRolesService;
use Behat\Service\Role\Query\FindOneRoleService;
use Behat\Service\SharedStorageInterface;
use Cubiche\Core\Validator\Assert;

/**
 * RoleContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class RoleContext implements Context
{
    /**
     * @var CreateRoleService
     */
    protected $createRoleService;

    /**
     * @var AddPermissionsToRoleService
     */
    protected $addPermissionsToRoleService;

    /**
     * @var RemovePermissionsFromRoleService
     */
    protected $removePermissionsFromRoleService;

    /**
     * @var FindOneRoleService
     */
    protected $findOneRoleService;

    /**
     * @var FindAllRolesService
     */
    protected $findAllRolesService;

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * RoleContext constructor.
     *
     * @param CreateRoleService                $createRoleService
     * @param AddPermissionsToRoleService      $addPermissionsToRoleService
     * @param RemovePermissionsFromRoleService $removePermissionsFromRoleService
     * @param FindOneRoleService               $findOneRoleService
     * @param FindAllRolesService              $findAllRolesService
     * @param SharedStorageInterface           $sharedStorage
     */
    public function __construct(
        CreateRoleService $createRoleService,
        AddPermissionsToRoleService $addPermissionsToRoleService,
        RemovePermissionsFromRoleService $removePermissionsFromRoleService,
        FindOneRoleService $findOneRoleService,
        FindAllRolesService $findAllRolesService,
        SharedStorageInterface $sharedStorage
    ) {
        $this->createRoleService = $createRoleService;
        $this->addPermissionsToRoleService = $addPermissionsToRoleService;
        $this->removePermissionsFromRoleService = $removePermissionsFromRoleService;
        $this->findOneRoleService = $findOneRoleService;
        $this->findAllRolesService = $findAllRolesService;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given I want to add a new role
     */
    public function iWantToAddANewRole()
    {
        $this->createRoleService->init();
    }

    /**
     * @When I specify the name as :name
     *
     * @param string $name
     */
    public function iSpecifyTheNameAs($name)
    {
        $this->createRoleService->specifyName($name);
    }

    /**
     * @When /^I specify the (permission(?:|s) "[^"]+")$/
     *
     * @param array $permissions
     */
    public function iSpecifyThePermissionsAsAnd(array $permissions)
    {
        $this->createRoleService->specifyPermissions($permissions);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createRoleService->create();
    }

    /**
     * @Then I should be notified that it has been successfully created
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyCreated()
    {
        Assert::true($this->createRoleService->successful());
    }

    /**
     * @Then I should be notified that the name is required
     */
    public function iShouldBeNotifiedThatTheNameIsRequired()
    {
        Assert::true($this->createRoleService->hasValidationErrorKey('name'));
    }

    /**
     * @Then I should be notified that role with this name already exists
     */
    public function iShouldBeNotifiedThatRoleWithThisNameAlreadyExists()
    {
        Assert::true($this->createRoleService->hasValidationErrorKey('name'));
    }

    /**
     * @Then I should be notified that I don't have permission to do it
     */
    public function iShouldBeNotifiedThatIDontHavePermissionToDoIt()
    {
        Assert::true($this->createRoleService->hasErrorWithCode(401));
    }

    /**
     * @Then this role should appear in the list
     */
    public function theRoleShouldAppearInTheList()
    {
        $roleId = $this->createRoleService->roleId();
        Assert::notNull($roleId);

        Assert::notNull($this->findOneRoleById($roleId));
    }

    /**
     * @Then there should still be only one role with the name :name
     *
     * @param string $name
     */
    public function thereShouldStillBeOnlyOneRoleWithTheName($name)
    {
        $this->findAllRolesService->init();
        $roles = array_filter($this->findAllRolesService->getRoles(), function ($role) use ($name) {
            return $role['name'] == $name;
        });

        Assert::count($roles, 1);
    }

    /**
     * @Given /^I want to modify (the "[^"]+" role(?:|s))$/
     *
     * @param array $roles
     */
    public function iWantToModifyTheRole(array $roles)
    {
        Assert::notEmpty($roles);
        $this->sharedStorage->set('role', $roles[0]);
    }

    /**
     * @When /^I specify the (permission(?:|s) "[^"]+") to add its$/
     *
     * @param array $permissions
     */
    public function iSpecifyThePermissionsToAddIts(array $permissions)
    {
        $this->addPermissionsToRoleService->init();
        $this->addPermissionsToRoleService->specifyRoleId($this->sharedStorage->get('role'));
        $this->addPermissionsToRoleService->specifyPermissions($permissions);
    }

    /**
     * @When I add the permissions
     */
    public function iAddThePermissions()
    {
        $this->addPermissionsToRoleService->addIt();
    }

    /**
     * @Then I should be notified that the permissions have been successfully added
     */
    public function iShouldBeNotifiedThatThePermissionsHaveBeenSuccessfullyAdded()
    {
        Assert::true($this->addPermissionsToRoleService->successful());
    }

    /**
     * @Then /^this role should have the ("[^"]+" permission(?:|s))$/
     *
     * @param array $permissions
     */
    public function thisRoleShouldHaveThePermissions(array $permissions)
    {
        $role = $this->addPermissionsToRoleService->role();
        foreach ($permissions as $permission) {
            Assert::true(in_array($permission, $role['permissions']));
        }
    }

    /**
     * @When /^I specify the (permission(?:|s) "[^"]+") to remove its$/
     *
     * @param array $permissions
     */
    public function iSpecifyThePermissionsToRemoveIts(array $permissions)
    {
        $this->removePermissionsFromRoleService->init();
        $this->removePermissionsFromRoleService->specifyRoleId($this->sharedStorage->get('role'));
        $this->removePermissionsFromRoleService->specifyPermissions($permissions);
    }

    /**
     * @When I remove the permissions
     */
    public function iRemoveThePermissions()
    {
        $this->removePermissionsFromRoleService->removeIt();
    }

    /**
     * @Then I should be notified that the permissions have been successfully removed
     */
    public function iShouldBeNotifiedThatThePermissionsHaveBeenSuccessfullyRemoved()
    {
        Assert::true($this->removePermissionsFromRoleService->successful());
    }

    /**
     * @Then /^this role should not have the ("[^"]+" permission(?:|s))$/
     *
     * @param array $permissions
     */
    public function thisRoleShouldNotHaveThePermissions(array $permissions)
    {
        $role = $this->removePermissionsFromRoleService->role();
        foreach ($permissions as $permission) {
            Assert::false(in_array($permission, $role['permissions']));
        }
    }

    /**
     * @Given I want to see all the roles
     */
    public function iWantToSeeAllTheRoles()
    {
        $this->findAllRolesService->init();
    }

    /**
     * @When I list its
     */
    public function iListIts()
    {
    }

    /**
     * @Then /^I should see (\d) roles$/
     *
     * @param int $count
     */
    public function iShouldSeeRoles($count)
    {
        Assert::count($this->findAllRolesService->getRoles(), $count);
    }

    /**
     * @param string $roleId
     *
     * @return array|null
     */
    protected function findOneRoleById($roleId)
    {
        $this->findOneRoleService->init();
        $this->findOneRoleService->specifyRoleId($roleId);

        return $this->findOneRoleService->getRole();
    }
}
