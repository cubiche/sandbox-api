<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Command\Core\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * InstallCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class InstallCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:install')
            ->setDescription('Sandbox install command.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Installing </info>Sandbox <info>application</info>');

        $this
            ->setupStep($input, $output)
        ;

        $output->writeln('<info>Sandbox application has been successfully installed.</info>');
    }

    /**
     * Setup each step.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function setupStep(InputInterface $input, OutputInterface $output)
    {
        return $this
            ->removeDatabases($input, $output)
            ->setupSystem($input, $output)
            ->setupSecurity($input, $output)
        ;
    }

    /**
     * Setup the app system data.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function removeDatabases(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Removing the databases.</info>');

        $commandInput = new ArrayInput(array(
            'command' => 'cubiche:mongodb:schema-drop',
            '--env' => $input->getOption('env'),
        ));

        return $this
            ->runCommand('cubiche:mongodb:schema-drop', $commandInput, $output)
        ;
    }

    /**
     * Setup the app system data.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function setupSystem(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Setting up system.</info>');

        return $this
            ->creatingLanguages($input, $output)
            ->creatingCurrencies($input, $output)
            ->creatingCountries($input, $output)
        ;
    }

    /**
     * Setup the app security data.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function setupSecurity(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Setting up security.</info>');

        return $this
            ->creatingRoles($input, $output)
            ->creatingUsers($input, $output)
            ->creatingConferences($input, $output)
        ;
    }

    /**
     * Creating the language list.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function creatingLanguages(InputInterface $input, OutputInterface $output)
    {
        $commandInput = new ArrayInput(array(
            'command' => 'app:language-create-all',
            '--env' => $input->getOption('env'),
        ));

        return $this
            ->runCommand('app:language-create-all', $commandInput, $output)
        ;
    }

    /**
     * Creating the currency list.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function creatingCurrencies(InputInterface $input, OutputInterface $output)
    {
        $commandInput = new ArrayInput(array(
            'command' => 'app:currency-create-all',
            '--env' => $input->getOption('env'),
        ));

        return $this
            ->runCommand('app:currency-create-all', $commandInput, $output)
        ;
    }

    /**
     * Creating the cpuntry list.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function creatingCountries(InputInterface $input, OutputInterface $output)
    {
        $commandInput = new ArrayInput(array(
            'command' => 'app:country-create-all',
            '--env' => $input->getOption('env'),
        ));

        return $this
            ->runCommand('app:country-create-all', $commandInput, $output)
        ;
    }

    /**
     * Creating the role list.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function creatingRoles(InputInterface $input, OutputInterface $output)
    {
        $roles = array('ADMIN' => array('app'), 'CUSTOMER' => array('app.open'));
        foreach ($roles as $roleName => $permissions) {
            $commandInput = new ArrayInput(array(
                'command' => 'app:role-create',
                'name' => $roleName,
                'permissions' => $permissions,
                '--env' => $input->getOption('env'),
            ));

            $this
                ->runCommand('app:role-create', $commandInput, $output)
            ;
        }

        return $this;
    }

    /**
     * Creating the user list.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function creatingUsers(InputInterface $input, OutputInterface $output)
    {
        $users = array(
            'ADMIN' => array(
                array('username' => 'admin', 'password' => 'admin', 'email' => 'admin@cubiche.org'),
            ),
            'CUSTOMER' => array(
                array('username' => 'customer', 'password' => 'customer', 'email' => 'customer@cubiche.org'),
            ),
        );

        foreach ($users as $roleName => $items) {
            foreach ($items as $user) {
                $commandInput = new ArrayInput(array(
                    'command' => 'app:user-create',
                    'username' => $user['username'],
                    'password' => $user['password'],
                    'email' => $user['email'],
                    'roles' => array($roleName),
                    '--env' => $input->getOption('env'),
                ));

                $this
                    ->runCommand('app:user-create', $commandInput, $output)
                ;
            }
        }

        return $this;
    }

    /**
     * Creating the conference list.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     */
    protected function creatingConferences(InputInterface $input, OutputInterface $output)
    {
        $conferences = array(
            array(
                'name' => array('en_US' => 'DDD Conference', 'es_ES' => 'Conferencia DDD'),
                'city' => array('en_US' => 'Amsterdam', 'es_ES' => 'Amsterdam'),
                'countryCode' => 'NL',
                'availableTickets' => 245,
                'price' => 299,
                'startAt' => '2018-07-29',
                'endAt' => '2018-07-31',
            ),
            array(
                'name' => array('en_US' => 'International PHP Conference', 'es_ES' => 'Conferencia Internacional de PHP'),
                'city' => array('en_US' => 'Berlin', 'es_ES' => 'Berlin'),
                'countryCode' => 'DE',
                'availableTickets' => 568,
                'price' => 549,
                'startAt' => '2018-10-12',
                'endAt' => '2018-10-14',
            ),
            array(
                'name' => array('en_US' => 'ZendCon', 'es_ES' => 'ZendCon'),
                'city' => array('en_US' => 'Rome', 'es_ES' => 'Roma'),
                'countryCode' => 'IT',
                'availableTickets' => 1200,
                'price' => 349,
                'startAt' => '2018-08-17',
                'endAt' => '2018-08-20',
            ),
            array(
                'name' => array('en_US' => 'Polycon', 'es_ES' => 'Polycon'),
                'city' => array('en_US' => 'Paris', 'es_ES' => 'Paris'),
                'countryCode' => 'FR',
                'availableTickets' => 480,
                'price' => 150,
                'startAt' => '2018-09-03',
                'endAt' => '2018-09-05',
            ),
            array(
                'name' => array('en_US' => 'Mobile world congress', 'es_ES' => 'Congreso mundial de moviles'),
                'city' => array('en_US' => 'Barcelona', 'es_ES' => 'Barcelona'),
                'countryCode' => 'ES',
                'availableTickets' => 6240,
                'price' => 1250,
                'startAt' => '2018-02-26',
                'endAt' => '2018-03-01',
            ),
        );

        foreach ($conferences as $conference) {
            $name = array_map(function ($key, $value) {
                return $key.':'.$value;
            }, array_keys($conference['name']), $conference['name']);

            $city = array_map(function ($key, $value) {
                return $key.':'.$value;
            }, array_keys($conference['city']), $conference['city']);

            $commandInput = new ArrayInput(array(
                'command'            => 'app:conference-create',
                '--name'             => $name,
                '--city'             => $city,
                '--countryCode'      => $conference['countryCode'],
                '--availableTickets' => $conference['availableTickets'],
                '--price'            => $conference['price'],
                '--startAt'          => $conference['startAt'],
                '--endAt'            => $conference['endAt'],
                '--env'              => $input->getOption('env'),
            ));

            $this
                ->runCommand('app:conference-create', $commandInput, $output)
            ;
        }

        return $this;
    }
}
