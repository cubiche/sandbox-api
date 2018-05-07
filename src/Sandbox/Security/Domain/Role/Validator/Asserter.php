<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Role\Validator;

use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleByName;

/**
 * Asserter class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Asserter
{
    /**
     * @var QueryBus
     */
    protected $queryBus;

    /**
     * Asserter constructor.
     *
     * @param QueryBus $queryBus
     */
    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function uniqueRoleName($value, $message = null, $propertyPath = null)
    {
        $role = $this->queryBus->dispatch(new FindOneRoleByName($value));
        if ($role !== null) {
            $message = sprintf(
                Assert::generateMessage($message ?: 'Role name "%s" expected to be unique.'),
                Assert::stringify($value)
            );

            throw Assert::createException($value, $message, Assert::INVALID_UNIQUE_VALUE, $propertyPath);
        }

        return true;
    }
}
