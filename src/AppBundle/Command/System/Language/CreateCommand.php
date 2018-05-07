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
use Sandbox\System\Domain\Language\Command\CreateLanguageCommand;
use Sandbox\System\Domain\Language\LanguageId;
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
            ->setName('app:language-create')
            ->addArgument('code', InputArgument::REQUIRED, 'The language code.')
            ->addArgument('name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The language name.')
            ->setDescription('Creates a new language.')
            ->setHelp('This command allows you to create a language...')
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

        $languageCode = $input->getArgument('code');
        $name = $this->normalizeTranslations($input->getArgument('name'));

        try {
            $output->writeln('<info>Creating a new </info>language');

            $languageId = LanguageId::next()->toNative();
            $this->commandBus()->dispatch(
                new CreateLanguageCommand($languageId, $languageCode, $name, $defaultLocale)
            );

            $output->writeln(
                '<info>A new language with id </info>"'.$languageId.'"<info> has been successfully created.</info>'
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
            list($languageCode, $languageName) = explode(':', $item);

            $name[$languageCode] = $languageName;
        }

        return $name;
    }
}
