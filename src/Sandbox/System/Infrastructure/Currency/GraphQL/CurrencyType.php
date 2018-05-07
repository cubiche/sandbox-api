<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Currency\GraphQL;

use Sandbox\System\Domain\Currency\ReadModel\Currency;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * CurrencyType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (Currency $currency, $args) {
                    return $currency->id()->toNative();
                },
            ])
            ->addField('name', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Currency $currency, $args) {
                    return $currency->name()->toNative();
                },
            ])
            ->addField('code', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Currency $currency, $args) {
                    return $currency->code()->toNative();
                },
            ])
            ->addField('symbol', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Currency $currency, $args) {
                    return $currency->symbol()->toNative();
                },
            ])
        ;

//        if ($this->system()) {
//            $config
//                ->addField('enabled', [
//                    'type' => new NonNullType(new BooleanType()),
//                    'resolve' => function (Currency $currency, $args) {
//                        return $currency->isEnabled();
//                    },
//                ])
//            ;
//        }
    }
}
