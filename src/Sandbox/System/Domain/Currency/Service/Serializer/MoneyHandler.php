<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\Service\Serializer;

use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Handler\HandlerSubscriberInterface;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use Cubiche\Domain\System\Real;
use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\Money;

/**
 * MoneyHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MoneyHandler implements HandlerSubscriberInterface
{
    /**
     * @param SerializationVisitor $visitor
     * @param Money                $money
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(
        SerializationVisitor $visitor,
        Money $money,
        array $type,
        ContextInterface $context
    ) {
        return array(
            'amount' => $context->navigator()->accept(
                $money->amountToReal(),
                array('name' => Real::class, 'params' => array()),
                $context
            ),
            'currency' => $context->navigator()->accept(
                $money->currency(),
                array('name' => CurrencyCode::class, 'params' => array()),
                $context
            ),
        );
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param array                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, array $data, array $type, ContextInterface $context)
    {
        $amount = $visitor->visitInteger($data['amount'], array('name' => 'int', 'params' => array()), $context);
        $currency = $visitor->visitString($data['currency'], array('name' => 'string', 'params' => array()), $context);

        return Money::fromNative($amount, $currency);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedHandlers()
    {
        return array(
            'serializers' => array(
                Money::class => 'serialize',
            ),
            'deserializers' => array(
                Money::class => 'deserialize',
            ),
        );
    }
}
