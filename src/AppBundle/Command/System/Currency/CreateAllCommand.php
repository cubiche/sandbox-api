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
use Sandbox\System\Domain\Currency\ReadModel\Query\FindOneCurrencyByCode;
use Sandbox\System\Infrastructure\Currency\Service\CurrencyProvider;
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
            ->setName('app:currency-create-all')
            ->setDescription('Creates all the system currencies.')
            ->setHelp('This command allows you to create all the system currencies...')
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
        $output->writeln('<info>Creating </info>currencies ...');

        /** @var CurrencyProvider $provider */
        $provider = $this->get('app.service.currency_provider');
        $availableLocales = $this->getParameter('app.available_locales');
        $availableCurrencies = $this->getParameter('app.available_currencies');

        foreach ($provider->getAllCurrencies($availableLocales) as $currencyCode => $currencyData) {
            if (!in_array($currencyCode, $availableCurrencies)) {
                continue;
            }

            $name = array_map(function ($key, $value) {
                return $key.':'.$value;
            }, array_keys($currencyData['names']), $currencyData['names']);

            $currency = $this->queryBus()->dispatch(new FindOneCurrencyByCode($currencyCode));
            if ($currency !== null) {
                $commandInput = new ArrayInput(array(
                    'command' => 'app:currency-update',
                    'id' => $currency->currencyId()->toNative(),
                    'name' => $name,
                    '--env' => $input->getOption('env'),
                ));

                $this
                    ->runCommand('app:currency-update', $commandInput, $output)
                ;
            } else {
                $commandInput = new ArrayInput(array(
                    'command' => 'app:currency-create',
                    'code' => $currencyCode,
                    'symbol' => $currencyData['symbol'],
                    'name' => $name,
                    '--env' => $input->getOption('env'),
                ));

                $this
                    ->runCommand('app:currency-create', $commandInput, $output)
                ;
            }
        }
    }
}
