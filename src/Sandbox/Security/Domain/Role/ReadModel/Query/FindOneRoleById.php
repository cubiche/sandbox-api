<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role\ReadModel\Query;

use Cubiche\Core\Cqrs\Query\Query;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * FindOneRoleById class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneRoleById extends Query
{
    /**
     * @var string
     */
    protected $roleId;

    /**
     * FindOneRoleById constructor.
     *
     * @param string $roleId
     */
    public function __construct($roleId)
    {
        $this->roleId = $roleId;
    }

    /**
     * @return string
     */
    public function roleId()
    {
        return $this->roleId;
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    public static function loadValidatorMetaData(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('roleId', Assertion::uuid()->notBlank());
    }
}
