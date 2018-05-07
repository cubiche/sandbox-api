<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Transform;

use Behat\Behat\Context\Context;
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
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * RoleContext constructor.
     *
     * @param $sharedStorage
     */
    public function __construct($sharedStorage)
    {
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Transform /^the role(?:|s) "([^"]+)"$/
     * @Transform /^the "([^"]+)" role(?:|s)$/
     * @Transform :roleName
     *
     * @param string $roleName
     *
     * @return array
     */
    public function getRolesByName($roleName)
    {
        $roles = array();

        $roleNames = explode(',', str_replace(' ', '', $roleName));
        foreach ($roleNames as $roleName) {
            Assert::true($this->sharedStorage->has('role-'.$roleName));

            $roles[] = $this->sharedStorage->get('role-'.$roleName);
        }

        return $roles;
    }
}
