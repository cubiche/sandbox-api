<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency;

use Cubiche\Domain\Model\ValueObjectInterface;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\Real;

/**
 * Money class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Money implements ValueObjectInterface
{
    /**
     * @var CurrencyCode
     */
    protected $currency;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $amount;

    /**
     * @param Integer      $amount
     * @param CurrencyCode $currency
     */
    public function __construct(Integer $amount, CurrencyCode $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param float  $amount
     * @param string $currency
     *
     * @return \Sandbox\System\Domain\Currency\Money
     */
    public static function fromNative($amount, $currency)
    {
        $amount = Real::fromNative($amount);
        $amountFormated = \money_format('%i', $amount->toNative());
        $cents = \filter_var($amountFormated, FILTER_SANITIZE_NUMBER_INT);

        if ((int) $cents === 0) {
            return new static(Integer::fromNative(0), CurrencyCode::fromNative($currency));
        }

        return new static(Integer::fromNative($cents), CurrencyCode::fromNative($currency));
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return Real
     */
    public function amountToReal()
    {
        return $this->amount()->divInteger(Integer::fromNative(100), 2);
    }

    /**
     * @return CurrencyCode
     */
    public function currency()
    {
        return $this->currency;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function units()
    {
        if ($this->amount()->toNative() < 100) {
            return Integer::fromNative(0);
        }

        $string = $this->amount()->__toString();

        return Integer::fromNative(substr($string, 0, strlen($string) - 2));
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function cents()
    {
        if ($this->amount()->toNative() < 100) {
            return $this->amount();
        }

        $string = $this->amount()->__toString();

        return Integer::fromNative((int) substr($string, -2, 2));
    }

    /**
     * @param Money $money
     *
     * @return bool
     */
    public function equals($money)
    {
        return  Util::classEquals($this, $money) &&
            $this->amount()->equals($money->amount) &&
            $this->currency()->equals($money->currency())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $cents = $this->cents()->toNative();
        $cents = $cents < 10 ? '0'.\strval($cents) : \strval($cents);

        return \sprintf('%s %d.%s', $this->currency(), $this->units()->toNative(), $cents);
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->__toString();
    }
}
