<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Currency\GraphQL\Query;

use Sandbox\System\Application\Currency\ReadModel\Controller\CurrencyController;
use Sandbox\System\Domain\Currency\ReadModel\Currency;
use Sandbox\System\Infrastructure\Currency\GraphQL\CurrencyType;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;

/**
 * FindAllCurrencies class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllCurrencies extends AbstractField
{
    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Currency[]
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var CurrencyController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.currency');

        return $controller->findAllAction();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'currencies';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new CurrencyType());
    }
}
