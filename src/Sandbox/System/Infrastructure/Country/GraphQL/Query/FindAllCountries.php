<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Country\GraphQL\Query;

use Sandbox\System\Application\Country\ReadModel\Controller\CountryController;
use Sandbox\System\Infrastructure\Country\GraphQL\CountryType;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;

/**
 * FindAllCountries class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllCountries extends AbstractField
{
    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var CountryController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.country');

        return $controller->findAllAction();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'countries';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new CountryType());
    }
}
