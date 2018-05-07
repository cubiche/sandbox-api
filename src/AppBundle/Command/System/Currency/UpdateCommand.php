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
use Sandbox\System\Domain\Currency\Command\UpdateCurrencyNameCommand;
use Cubiche\Core\Validator\Exception\ValidationException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * UpdateCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UpdateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:currency-update')
            ->addArgument('id', InputArgument::REQUIRED, 'The currency id.')
            ->addArgument('name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The currency name.')
            ->setDescription('Updates a given currency.')
            ->setHelp('This command allows you to update a currency...')
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
        $currencyId = $input->getArgument('id');
        $name = $this->normalizeTranslations($input->getArgument('name'));

        try {
            $output->writeln('<info>Updating a </info>currency');
            $this->commandBus()->dispatch(new UpdateCurrencyNameCommand($currencyId, $name));

            $output->writeln(
                '<info>The currency with id </info>"'.$currencyId.'"<info> has been successfully updated.</info>'
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
