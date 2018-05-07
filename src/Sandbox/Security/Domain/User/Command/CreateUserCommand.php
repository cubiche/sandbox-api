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
 * CreateUserCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateUserCommand extends Command
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var bool
     */
    protected $verificationByEmail;

    /**
     * @var array
     */
    protected $roles;

    /**
     * CreateUserCommand constructor.
     *
     * @param string $userId
     * @param string $username
     * @param string $password
     * @param string $email
     * @param array  $roles
     * @param bool   $verificationByEmail
     */
    public function __construct(
        $userId,
        $username,
        $password,
        $email,
        array $roles = array(),
        $verificationByEmail = true
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
        $this->verificationByEmail = $verificationByEmail;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function roles()
    {
        return $this->roles;
    }

    /**
     * @return bool
     */
    public function verificationByEmail()
    {
        return $this->verificationByEmail;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('userId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('username', Assertion::uniqueUsername());
        $classMetadata->addPropertyConstraint('password', Assertion::string()->notBlank());
        $classMetadata->addPropertyConstraint('email', Assertion::uniqueEmail());
        $classMetadata->addPropertyConstraint('roles', Assertion::isArray()->each(null, Assertion::uuid()->notBlank()));
        $classMetadata->addPropertyConstraint('verificationByEmail', Assertion::boolean());
    }
}
