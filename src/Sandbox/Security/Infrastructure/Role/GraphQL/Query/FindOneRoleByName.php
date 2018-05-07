<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\Role\GraphQL\Query;

use Sandbox\Security\Application\Role\ReadModel\Controller\RoleController;
use Sandbox\Security\Domain\Role\ReadModel\Role;
use Sandbox\Security\Infrastructure\Role\RoleType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * FindOneRoleByName class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneRoleByName extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('name', new NonNullType(new StringType()))
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
        /** @var RoleController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.role');

        return $controller->findOneByNameAction($args['name']);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new RoleType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'roleByName';
    }
}
