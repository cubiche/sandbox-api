<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Language\GraphQL;

use Sandbox\System\Domain\Language\ReadModel\Language;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * LanguageType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageType extends AbstractObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (Language $language, $args) {
                    return $language->id()->toNative();
                },
            ])
            ->addField('name', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Language $language, $args) {
                    return $language->name()->toNative();
                },
            ])
            ->addField('code', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Language $language, $args) {
                    return $language->code()->toNative();
                },
            ])
        ;

//        if ($this->system()) {
//            $config
//                ->addField('enabled', [
//                    'type' => new NonNullType(new BooleanType()),
//                    'resolve' => function (Language $language, $args) {
//                        return $language->isEnabled();
//                    },
//                ])
//            ;
//        }
    }
}
