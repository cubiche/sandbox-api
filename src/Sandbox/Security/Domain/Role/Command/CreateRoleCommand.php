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
 * CreateRoleCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateRoleCommand extends Command
{
    /**
     * @var string
     */
    protected $roleId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $permissions;

    /**
     * CreateRoleCommand constructor.
     *
     * @param string $roleId
     * @param string $name
     * @param array  $permissions
     */
    public function __construct($roleId, $name, array $permissions = array())
    {
        $this->roleId = $roleId;
        $this->name = strtoupper($name);
        $this->permissions = $permissions;
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
    public function name()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    public static function loadValidatorMetaData(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('roleId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('name', Assertion::uniqueRoleName());

        $classMetadata->addPropertyConstraint(
            'permissions',
            Assertion::isArray()->each(null, Assertion::string()->notBlank())
        );
    }
}
