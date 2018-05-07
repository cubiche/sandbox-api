<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $this->addEmailsSection($rootNode);
        $this->addAccessControlSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return ArrayNodeDefinition
     */
    protected function addEmailsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('sender')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('Example.com')->end()
                        ->scalarNode('address')->defaultValue('no-reply@example.com')->end()
                    ->end()
                ->end()
                ->arrayNode('emails')
                    ->useAttributeAsKey('code')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('subject')->cannotBeEmpty()->end()
                            ->scalarNode('template')->cannotBeEmpty()->end()
                            ->booleanNode('enabled')->defaultTrue()->end()
                            ->arrayNode('sender')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('address')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return ArrayNodeDefinition
     */
    protected function addAccessControlSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('access_control')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('mappings')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) {
                                        return ['type' => $v];
                                    })
                                ->end()
                                ->treatNullLike([])
                                ->performNoDeepMerging()
                                ->children()
                                    ->scalarNode('dir')->end()
                                    ->scalarNode('prefix')->end()
                                    ->scalarNode('separator')->defaultValue('/')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
