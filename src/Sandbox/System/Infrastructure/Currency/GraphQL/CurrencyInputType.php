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

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * CurrencyInputType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyInputType extends AbstractInputObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('code', new NonNullType(new StringType()))
            ->addField('name', new NonNullType(new StringType()))
        ;
    }
}
