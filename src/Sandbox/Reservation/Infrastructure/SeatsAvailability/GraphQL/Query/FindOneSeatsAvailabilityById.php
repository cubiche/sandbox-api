<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Infrastructure\SeatsAvailability\GraphQL\Query;

use Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\SeatsAvailability;
use Sandbox\Reservation\Infrastructure\SeatsAvailability\GraphQL\SeatsAvailabilityType;
use Sandbox\Reservation\Application\SeatsAvailability\ReadModel\Controller\SeatsAvailabilityController;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;

/**
 * FindOneSeatsAvailabilityById class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneSeatsAvailabilityById extends AbstractField
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
     * @return SeatsAvailability
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var SeatsAvailabilityController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.seats_availability');

        return $controller->findOneByIdAction($args['conferenceId']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seatsAvailability';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new SeatsAvailabilityType();
    }
}
