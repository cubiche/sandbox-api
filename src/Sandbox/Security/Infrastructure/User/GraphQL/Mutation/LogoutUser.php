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

use Sandbox\Security\Application\User\Controller\SecurityController;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IdType;

/**
 * LogoutUser class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LogoutUser extends AbstractField
{
    /**
     * @param FieldConfig $config
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('userId', new NonNullType(new IdType()))
        ;
    }

    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return bool
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var SecurityController $controller */
        $controller = $info->getContainer()->get('app.controller.security');

        return $controller->logoutAction(
            $args['userId']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new BooleanType();
    }
}
