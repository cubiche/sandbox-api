<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * VerifyUserCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class VerifyUserCommand extends Command
{
    /**
     * @var string
     */
    protected $emailVerificationToken;

    /**
     * VerifyUserCommand constructor.
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
