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
 * LoginUserCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LoginUserCommand extends Command
{
    /**
     * @var string
     */
    protected $usernameOrEmail;

    /**
     * @var string
     */
    protected $password;

    /**
     * LoginUserCommand constructor.
     *
     * @param string $usernameOrEmail
     * @param string $password
     */
    public function __construct($usernameOrEmail, $password)
    {
        $this->usernameOrEmail = $usernameOrEmail;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function usernameOrEmail()
    {
        return $this->usernameOrEmail;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('usernameOrEmail', Assertion::string()->notBlank());
        $classMetadata->addPropertyConstraint('password', Assertion::string()->notBlank());
    }
}
