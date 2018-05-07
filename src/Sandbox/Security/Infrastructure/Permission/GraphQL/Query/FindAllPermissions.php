<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\Permission\GraphQL\Query;

use Sandbox\Security\Application\Permission\ReadModel\Controller\PermissionController;
use Sandbox\Security\Domain\Permission\ReadModel\Permission;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * FindAllPermissions class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllPermissions extends AbstractField
{
    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return string[]
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var PermissionController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.permission');

        return $controller->findAllAction();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new StringType());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'permissions';
    }
}
