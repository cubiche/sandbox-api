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

use Sandbox\Security\Application\User\ReadModel\Controller\SecurityController;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Infrastructure\User\UserType;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;

/**
 * FindAuthenticatedUser class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAuthenticatedUser extends AbstractField
{
    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return User
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var SecurityController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.security');

        return $controller->findAuthenticatedUserAction();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new UserType();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'me';
    }
}
