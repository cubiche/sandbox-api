<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Language\GraphQL\Query;

use Sandbox\System\Application\Language\ReadModel\Controller\LanguageController;
use Sandbox\System\Domain\Language\ReadModel\Language;
use Sandbox\System\Infrastructure\Language\GraphQL\LanguageType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * FindOneLanguage class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneLanguage extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('code', new NonNullType(new StringType()))
        ;
    }

    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return Language
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var LanguageController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.language');

        return $controller->findOneByCodeAction($args['code']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new LanguageType();
    }
}
