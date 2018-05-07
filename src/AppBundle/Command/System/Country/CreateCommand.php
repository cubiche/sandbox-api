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
use Sandbox\System\Domain\Country\Command\CreateCountryCommand;
use Sandbox\System\Domain\Country\CountryId;
use Cubiche\Core\Validator\Exception\ValidationException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CreateCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:country-create')
            ->addArgument('code', InputArgument::REQUIRED, 'The code of the country.')
            ->addArgument('name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The name translations.')
            ->setDescription('Creates a new country.')
            ->setHelp('This command allows you to create a country...')
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
        $defaultLocale = $this->getParameter('app.default_locale');

        $code = $input->getArgument('code');
        $name = $this->normalizeTranslations($input->getArgument('name'));

        try {
            $output->writeln('<info>Creating a new </info>country');

            $countryId = CountryId::next()->toNative();
            $this->commandBus()->dispatch(new CreateCountryCommand($countryId, $code, $name, $defaultLocale));

            $output->writeln(
                '<info>A new country with id </info>"'.$countryId.'"<info> has been successfully created.</info>'
            );
        } catch (ValidationException $e) {
            $this->printValidationErrors($e, $output);
        }
    }

    /**
     * @param array $values
     *
     * @return array
     */
    private function normalizeTranslations($values)
    {
        $translations = [];
        foreach ($values as $item) {
            list($country, $name) = explode(':', $item);

            $translations[$country] = $name;
        }

        return $translations;
    }
}
