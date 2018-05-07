<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Country\GraphQL;

use Sandbox\System\Domain\Country\ReadModel\Country;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * CountryType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (Country $country, $args) {
                    return $country->id()->toNative();
                },
            ])
            ->addField('name', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Country $country, $args) {
                    return $country->name()->toNative();
                },
            ])
        ;
    }
}
