<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\System\Country;

use AppBundle\Command\Core\Command;
use Sandbox\System\Domain\Country\ReadModel\Query\FindOneCountryByCode;
use Sandbox\System\Infrastructure\Country\Service\CountryProvider;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CreateAllCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateAllCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:country-create-all')
            ->setDescription('Creates all the system countries.')
            ->setHelp('This command allows you to create all the system countries...')
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
        $output->writeln('<info>Creating </info>countries ...');

        /** @var CountryProvider $provider */
        $provider = $this->get('app.service.country_provider');
        $availableLocales = $this->getParameter('app.available_locales');

        foreach ($provider->getAllCountries($availableLocales) as $countryCode => $countryData) {
            $name = array_map(function ($key, $value) {
                return $key.':'.$value;
            }, array_keys($countryData['names']), $countryData['names']);

            $country = $this->queryBus()->dispatch(new FindOneCountryByCode($countryCode));
            if ($country === null) {
                $commandInput = new ArrayInput(array(
                    'command' => 'app:country-create',
                    'code' => $countryCode,
                    'name' => $name,
                    '--env' => $input->getOption('env'),
                ));

                $this
                    ->runCommand('app:country-create', $commandInput, $output)
                ;
            }
        }
    }
}
