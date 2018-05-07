<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\System\Language;

use AppBundle\Command\Core\Command;
use Sandbox\System\Domain\Language\ReadModel\Query\FindOneLanguageByCode;
use Sandbox\System\Infrastructure\Language\Service\LanguageProvider;
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
            ->setName('app:language-create-all')
            ->setDescription('Creates all the system languages.')
            ->setHelp('This command allows you to create all the system languages...')
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
        $output->writeln('<info>Creating </info>languages ...');

        /** @var LanguageProvider $provider */
        $provider = $this->get('app.service.language_provider');
        $availableLocales = $this->getParameter('app.available_locales');

        foreach ($provider->getAllLanguages($availableLocales) as $languageCode => $languageData) {
            $name = array_map(function ($key, $value) {
                return $key.':'.$value;
            }, array_keys($languageData['names']), $languageData['names']);

            $language = $this->queryBus()->dispatch(new FindOneLanguageByCode($languageCode));
            if ($language !== null) {
                $commandInput = new ArrayInput(array(
                    'command' => 'app:language-update',
                    'id' => $language->languageId()->toNative(),
                    'name' => $name,
                    '--env' => $input->getOption('env'),
                ));

                $this
                    ->runCommand('app:language-update', $commandInput, $output)
                ;
            } else {
                $commandInput = new ArrayInput(array(
                    'command' => 'app:language-create',
                    'code' => $languageCode,
                    'name' => $name,
                    '--env' => $input->getOption('env'),
                ));

                $this
                    ->runCommand('app:language-create', $commandInput, $output)
                ;
            }
        }
    }
}
