<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\ReadModel\Query;

use Cubiche\Core\Cqrs\Query\Query;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * FindOneUserByUsername class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneUserByUsername extends Query
{
    /**
     * @var string
     */
    protected $username;

    /**
     * FindOneUserByUsername constructor.
     *
     * @param string $username
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('username', Assertion::string()->notBlank());
    }
}
