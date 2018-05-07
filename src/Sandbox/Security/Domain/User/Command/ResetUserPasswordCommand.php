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
 * ResetUserPasswordCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ResetUserPasswordCommand extends Command
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $password;

    /**
     * ResetUserPasswordCommand constructor.
     *
     * @param string $userId
     * @param string $password
     */
    public function __construct($userId, $password)
    {
        $this->userId = $userId;
        $this->password = $password;
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
    public function password()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('userId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('password', Assertion::string()->notBlank());
    }
}
