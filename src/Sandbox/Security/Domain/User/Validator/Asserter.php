<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Validator;

use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmail;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByUsername;
use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;

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
     * UniqueNameconstructor.
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
    public function uniqueUsername($value, $message = null, $propertyPath = null)
    {
        $user = $this->queryBus->dispatch(new FindOneUserByUsername($value));
        if ($user !== null) {
            $message = sprintf(
                Assert::generateMessage($message ?: 'Username "%s" expected to be unique.'),
                Assert::stringify($value)
            );

            throw Assert::createException($value, $message, Assert::INVALID_UNIQUE_VALUE, $propertyPath);
        }

        return true;
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
    public function uniqueEmail($value, $message = null, $propertyPath = null)
    {
        $user = $this->queryBus->dispatch(new FindOneUserByEmail($value));
        if ($user !== null) {
            $message = sprintf(
                Assert::generateMessage($message ?: 'Email "%s" expected to be unique.'),
                Assert::stringify($value)
            );

            throw Assert::createException($value, $message, Assert::INVALID_UNIQUE_VALUE, $propertyPath);
        }

        return true;
    }
}
