<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\Validator;

use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\ReadModel\Query\FindOneCurrencyByCode;
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
    public function currencyCode($value, $message = null, $propertyPath = null)
    {
        $message = sprintf(
            Assert::generateMessage($message ?: 'Value "%s" expected to be a valid currency code.'),
            Assert::stringify($value)
        );

        return Assert::inArray($value, CurrencyCode::toArray(), $message, $propertyPath);
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
    public function uniqueCurrencyCode($value, $message = null, $propertyPath = null)
    {
        $currency = $this->queryBus->dispatch(new FindOneCurrencyByCode($value));
        if ($currency !== null) {
            $message = sprintf(
                Assert::generateMessage($message ?: 'Currency code "%s" expected to be unique.'),
                Assert::stringify($value)
            );

            throw Assert::createException($value, $message, Assert::INVALID_UNIQUE_VALUE, $propertyPath);
        }

        return true;
    }
}
