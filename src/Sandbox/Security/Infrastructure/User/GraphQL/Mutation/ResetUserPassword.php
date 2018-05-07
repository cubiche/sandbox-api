<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\User\GraphQL\Mutation;

use Sandbox\Security\Application\User\Controller\UserController;
use Sandbox\Security\Application\User\ReadModel\Controller\UserController as ReadModelUserController;
use Sandbox\Security\Infrastructure\User\UserType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * ResetUserPassword class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ResetUserPassword extends AbstractField
{
    /**
     * @param FieldConfig $config
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('userId', new NonNullType(new IdType()))
            ->addArgument('password', new NonNullType(new StringType()))
        ;
    }

    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var UserController $controller */
        $controller = $info->getContainer()->get('app.controller.user');
        $controller->resetPasswordAction($args['id'], $args['password']);

        /** @var ReadModelUserController $readModelController */
        $readModelController = $info->getContainer()->get('app.controller.read_model.user');

        return $readModelController->findOneByIdAction($args['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new UserType();
    }
}
