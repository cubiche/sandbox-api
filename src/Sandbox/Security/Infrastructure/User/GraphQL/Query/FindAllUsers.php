<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\User\GraphQL\Query;

use Sandbox\Security\Application\User\ReadModel\Controller\UserController;
use Sandbox\Security\Infrastructure\User\UserType;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;

/**
 * FindAllUsers class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllUsers extends AbstractField
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
        /** @var UserController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.user');

        return $controller->findAllAction();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new UserType());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'users';
    }
}
