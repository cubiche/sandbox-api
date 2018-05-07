<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Conference;

use AppBundle\Command\Core\Command;
use Cubiche\Core\Validator\Exception\ValidationException;
use Sandbox\Conference\Domain\Command\CreateConferenceCommand;
use Sandbox\Conference\Domain\ConferenceId;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->setName('app:conference-create')
            ->addOption('name', '', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The conference name.')
            ->addOption('city', '', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The city name.')
            ->addOption('countryCode', '', InputOption::VALUE_REQUIRED, 'The country code.')
            ->addOption('availableTickets', '', InputOption::VALUE_REQUIRED, 'The conference available tickets.')
            ->addOption('price', '', InputOption::VALUE_REQUIRED, 'The conference price.')
            ->addOption('startAt', '', InputOption::VALUE_REQUIRED, 'The conference startAt date.')
            ->addOption('endAt', '', InputOption::VALUE_REQUIRED, 'The conference endAt date.')
            ->setDescription('Creates a new conference.')
            ->setHelp('This command allows you to create a conference...')
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
        $defaultCurrency = $this->getParameter('app.default_currency');

        $name = $this->normalizeTranslations($input->getOption('name'));
        $city = $this->normalizeTranslations($input->getOption('city'));
        $countryCode = $input->getOption('countryCode');
        $availableTickets = $input->getOption('availableTickets');
        $price = (float) $input->getOption('price');
        $startAt = $input->getOption('startAt');
        $endAt = $input->getOption('endAt');

        try {
            $output->writeln('<info>Creating a new </info>conference');

            $conferenceId = ConferenceId::next()->toNative();
            $this->commandBus()->dispatch(
                new CreateConferenceCommand(
                    $conferenceId,
                    $name,
                    $city,
                    $countryCode,
                    $availableTickets,
                    $price,
                    $defaultCurrency,
                    $startAt,
                    $endAt,
                    $defaultLocale
                )
            );

            $output->writeln(
                '<info>A new conference with id </info>"'.$conferenceId.'"<info> has been successfully created.</info>'
            );
        } catch (NotFoundException $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
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
        $name = [];
        foreach ($values as $item) {
            list($languageCode, $languageName) = explode(':', $item);

            $name[$languageCode] = $languageName;
        }

        return $name;
    }
}
