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
 * FindOneUserByEmail class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneUserByEmail extends Query
{
    /**
     * @var string
     */
    protected $email;

    /**
     * FindOneUserByEmail constructor.
     *
     * @param string $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('email', Assertion::email()->notBlank());
    }
}
