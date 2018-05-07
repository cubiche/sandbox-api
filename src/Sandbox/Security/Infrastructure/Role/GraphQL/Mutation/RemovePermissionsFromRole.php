<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\Role\GraphQL\Mutation;

use Sandbox\Security\Application\Role\Controller\RoleController as WriteModelController;
use Sandbox\Security\Application\Role\ReadModel\Controller\RoleController as ReadModelController;
use Sandbox\Security\Domain\Role\ReadModel\Role;
use Sandbox\Security\Infrastructure\Role\RoleType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * RemovePermissionsFromRole class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class RemovePermissionsFromRole extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('roleId', new NonNullType(new IdType()))
            ->addArgument('permissions', new NonNullType(new ListType(new StringType())))
        ;
    }

    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Role
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var WriteModelController $controller */
        $controller = $info->getContainer()->get('app.controller.role');
        foreach ($args['permissions'] as $permission) {
            $controller->removePermissionAction(
                $args['roleId'],
                $permission
            );
        }

        /** @var ReadModelController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.role');

        return $controller->findOneByIdAction($args['roleId']);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new RoleType();
    }
}
