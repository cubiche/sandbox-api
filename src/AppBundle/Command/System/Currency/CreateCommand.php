<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\System\Currency;

use AppBundle\Command\Core\Command;
use Sandbox\System\Domain\Currency\Command\CreateCurrencyCommand;
use Sandbox\System\Domain\Currency\CurrencyId;
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
            ->setName('app:currency-create')
            ->addArgument('code', InputArgument::REQUIRED, 'The currency code.')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The currency symbol.')
            ->addArgument('name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The currency name.')
            ->setDescription('Creates a new currency.')
            ->setHelp('This command allows you to create a currency...')
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

        $currencyCode = $input->getArgument('code');
        $currencySymbol = $input->getArgument('symbol');
        $name = $this->normalizeTranslations($input->getArgument('name'));

        try {
            $output->writeln('<info>Creating a new </info>currency');

            $currencyId = CurrencyId::next()->toNative();
            $this->commandBus()->dispatch(
                new CreateCurrencyCommand($currencyId, $currencyCode, $currencySymbol, $name, $defaultLocale)
            );

            $output->writeln(
                '<info>A new currency with id </info>"'.$currencyId.'"<info> has been successfully created.</info>'
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
        $name = [];
        foreach ($values as $item) {
            list($currencyCode, $currencyName) = explode(':', $item);

            $name[$currencyCode] = $currencyName;
        }

        return $name;
    }
}
