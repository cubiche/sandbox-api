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
 * FindOneRoleByName class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneRoleByName extends Query
{
    /**
     * @var string
     */
    protected $name;

    /**
     * FindOneRoleByName constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    public static function loadValidatorMetaData(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('name', Assertion::string()->notBlank());
    }
}
