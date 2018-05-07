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
 * FindOneUserByPasswordResetToken class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneUserByPasswordResetToken extends Query
{
    /**
     * @var string
     */
    protected $passwordResetToken;

    /**
     * FindOneUserByPasswordResetToken constructor.
     *
     * @param string $passwordResetToken
     */
    public function __construct($passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    }

    /**
     * @return string
     */
    public function passwordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('passwordResetToken', Assertion::string()->notBlank());
    }
}
