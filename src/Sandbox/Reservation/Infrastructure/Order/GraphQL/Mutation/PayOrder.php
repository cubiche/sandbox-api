<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Infrastructure\Order\GraphQL\Mutation;

use Sandbox\Payment\Application\Controller\PaymentController;
use Sandbox\Reservation\Application\Order\ReadModel\Controller\OrderController as ReadOrderController;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\OrderType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;

/**
 * PayOrder class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PayOrder extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('orderId', new NonNullType(new IdType()))
        ;
    }

    /**¯
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Payment
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var PaymentController $controller */
        $controller = $info->getContainer()->get('app.controller.payment');
        $controller->payAction($args['orderId']);

        /** @var ReadOrderController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.order');

        return $controller->findOneByIdAction($args['orderId']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payOrder';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new OrderType();
    }
}
