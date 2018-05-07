<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Infrastructure\GraphQL\Query;

use Sandbox\Conference\Application\ReadModel\Controller\ConferenceController;
use Sandbox\Conference\Domain\ReadModel\Conference;
use Sandbox\Conference\Infrastructure\GraphQL\ConferenceType;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;

/**
 * FindAllConferences class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllConferences extends AbstractField
{
    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Conference[]
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var ConferenceController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.conference');

        return $controller->findAllAction();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'conferences';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new ConferenceType());
    }
}
