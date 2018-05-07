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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Configuration class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AppExtension extends Extension
{
    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    protected $separator;

    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    protected $driverPaths = array();

    /**
     * @var string
     */
    protected $servicesDirectory = __DIR__.'/../../../app/config/services';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->loadServices($container);

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->processEmailConfiguration($config, $container);
        $this->processAccessControlConfiguration($config['access_control'], $container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function loadServices(ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator($this->getServicesDirectory()));
        $finder = new Finder();

        foreach ($finder->files()->in($this->getServicesDirectory()) as $file) {
            if (file_exists($file->getRealPath())) {
                $loader->load($file->getRealPath());
            }
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function processEmailConfiguration(array $config, ContainerBuilder $container)
    {
        $container->setParameter('app.mailer.sender_name', $config['sender']['name']);
        $container->setParameter('app.mailer.sender_address', $config['sender']['address']);
        $container->setParameter('app.mailer.emails', $config['emails']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function processAccessControlConfiguration(array $config, ContainerBuilder $container)
    {
        $this->loadMappingInformation($config['mappings'], $container);
        $container->setParameter('app.access_control.prefixes', array_flip($this->driverPaths));
        $container->setParameter('app.access_control.separator', $this->separator);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @throws \InvalidArgumentException
     */
    protected function loadMappingInformation(array $config, ContainerBuilder $container)
    {
        foreach ($config as $mappingName => $mappingConfig) {
            $mappingConfig = array_replace(array(
                'dir' => false,
                'prefix' => false,
            ), (array) $mappingConfig);

            $mappingConfig['dir'] = $container->getParameterBag()->resolveValue($mappingConfig['dir']);

            $this->assertValidMappingConfiguration($mappingConfig);
            $this->setMappingDriverConfig($mappingConfig, $mappingName);
        }
    }

    /**
     * Assertion if the specified mapping information is valid.
     *
     * @param array $mappingConfig
     *
     * @throws \InvalidArgumentException
     */
    protected function assertValidMappingConfiguration(array $mappingConfig)
    {
        if (!$mappingConfig['dir'] || !$mappingConfig['prefix']) {
            throw new \InvalidArgumentException(
                'Mapping definitions for security require at least the "dir" and "prefix" options.'
            );
        }

        if (!is_dir($mappingConfig['dir'])) {
            throw new \InvalidArgumentException(
                sprintf('Specified non-existing directory "%s" as mapping source.', $mappingConfig['dir'])
            );
        }
    }

    /**
     * Register the mapping driver configuration for later use with the metadata driver chain.
     *
     * @param array  $mappingConfig
     * @param string $mappingName
     *
     * @throws \InvalidArgumentException
     */
    protected function setMappingDriverConfig(array $mappingConfig, $mappingName)
    {
        $mappingDirectory = $mappingConfig['dir'];
        if (!is_dir($mappingDirectory)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid mapping path given. Cannot load mapping named "%s".', $mappingName)
            );
        }

        $prefix = $mappingConfig['prefix'];
        $this->driverPaths[$prefix] = realpath($mappingDirectory) ?: $mappingDirectory;
        $this->separator = $mappingConfig['separator'];
    }

    /**
     * Get the services configuration directory.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getServicesDirectory()
    {
        if (!is_dir($directory = $this->servicesDirectory)) {
            throw new \RuntimeException(
                sprintf('The services configuration directory "%s" does not exists.', $directory)
            );
        }

        return realpath($directory);
    }
}
