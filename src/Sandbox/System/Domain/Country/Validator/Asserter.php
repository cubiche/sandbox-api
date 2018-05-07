<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country\Validator;

use Sandbox\System\Domain\Country\ReadModel\Query\FindOneCountryByCode;
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
    public function uniqueCountryCode($value, $message = null, $propertyPath = null)
    {
        $country = $this->queryBus->dispatch(new FindOneCountryByCode($value));
        if ($country !== null) {
            $message = sprintf(
                Assert::generateMessage($message ?: 'Country code "%s" expected to be unique.'),
                Assert::stringify($value)
            );

            throw Assert::createException($value, $message, static::INVALID_UNIQUE_VALUE, $propertyPath);
        }

        return true;
    }
}
