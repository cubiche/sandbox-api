<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Infrastructure\GraphQL\Mutation;

use Cubiche\Domain\Localizable\LocalizableValueInterface;
use Sandbox\Conference\Application\Controller\ConferenceController;
use Sandbox\Conference\Application\ReadModel\Controller\ConferenceController as ReadConferenceController;
use Sandbox\Conference\Domain\ReadModel\Conference;
use Sandbox\Conference\Infrastructure\GraphQL\ConferenceType;
use Sandbox\Core\Infrastructure\GraphQL\Type\LocalizableType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * CreateConference class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateConference extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('name', new NonNullType(new LocalizableType()))
            ->addArgument('city', new NonNullType(new LocalizableType()))
            ->addArgument('countryCode', new NonNullType(new StringType()))
            ->addArgument('availableTickets', new NonNullType(new IntType()))
            ->addArgument('amount', new NonNullType(new FloatType()))
            ->addArgument('currency', new NonNullType(new StringType()))
            ->addArgument('startAt', new NonNullType(new StringType()))
            ->addArgument('endAt', new NonNullType(new StringType()))
        ;
    }

    /**¯
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Conference
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var ConferenceController $controller */
        $controller = $info->getContainer()->get('app.controller.conference');

        $conferenceId = $controller->createAction(
            $args['name'],
            $args['city'],
            $args['countryCode'],
            $args['availableTickets'],
            $args['amount'],
            $args['currency'],
            $args['startAt'],
            $args['endAt'],
            LocalizableValueInterface::DEFAULT_LOCALE
        );

        /** @var ReadConferenceController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.conference');

        return $controller->findOneByIdAction($conferenceId);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'createConference';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ConferenceType();
    }
}
