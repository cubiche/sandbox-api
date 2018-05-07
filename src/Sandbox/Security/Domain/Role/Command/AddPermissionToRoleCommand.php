<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * AddPermissionToRoleCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AddPermissionToRoleCommand extends Command
{
    /**
     * @var string
     */
    protected $roleId;

    /**
     * @var string
     */
    protected $permission;

    /**
     * AddPermissionToRoleCommand constructor.
     *
     * @param string $roleId
     * @param string $permission
     */
    public function __construct($roleId, $permission)
    {
        $this->roleId = $roleId;
        $this->permission = $permission;
    }

    /**
     * @return string
     */
    public function roleId()
    {
        return $this->roleId;
    }

    /**
     * @return string
     */
    public function permission()
    {
        return $this->permission;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('roleId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('permission', Assertion::string()->notBlank());
    }
}
