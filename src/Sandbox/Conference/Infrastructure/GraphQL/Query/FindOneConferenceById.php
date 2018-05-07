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

use Sandbox\Conference\Domain\ReadModel\Conference;
use Sandbox\Conference\Infrastructure\GraphQL\ConferenceType;
use Sandbox\Conference\Application\ReadModel\Controller\ConferenceController;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;

/**
 * FindOneConferenceById class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneConferenceById extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('conferenceId', new NonNullType(new IdType()))
        ;
    }

    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Conference
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var ConferenceController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.conference');

        return $controller->findOneByIdAction($args['conferenceId']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'conference';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ConferenceType();
    }
}
