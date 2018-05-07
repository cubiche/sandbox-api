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
 * AddUserRoleCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AddUserRoleCommand extends Command
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $roleId;

    /**
     * AddUserRoleCommand constructor.
     *
     * @param string $userId
     * @param string $roleId
     */
    public function __construct($userId, $roleId)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
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
    public function roleId()
    {
        return $this->roleId;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('userId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('roleId', Assertion::uuid()->notBlank());
    }
}
