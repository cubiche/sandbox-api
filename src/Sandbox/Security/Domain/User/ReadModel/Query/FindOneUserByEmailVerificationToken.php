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
 * FindOneUserByEmailVerificationToken class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneUserByEmailVerificationToken extends Query
{
    /**
     * @var string
     */
    protected $emailVerificationToken;

    /**
     * FindOneUserByEmailVerificationToken constructor.
     *
     * @param string $emailVerificationToken
     */
    public function __construct($emailVerificationToken)
    {
        $this->emailVerificationToken = $emailVerificationToken;
    }

    /**
     * @return string
     */
    public function emailVerificationToken()
    {
        return $this->emailVerificationToken;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('emailVerificationToken', Assertion::string()->notBlank());
    }
}
