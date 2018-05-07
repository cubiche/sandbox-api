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
use Sandbox\System\Domain\Language\Command\UpdateLanguageNameCommand;
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
            ->setName('app:language-update')
            ->addArgument('id', InputArgument::REQUIRED, 'The language id.')
            ->addArgument('name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The language name.')
            ->setDescription('Updates a given language.')
            ->setHelp('This command allows you to update a language...')
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
        $languageId = $input->getArgument('id');
        $name = $this->normalizeTranslations($input->getArgument('name'));

        try {
            $output->writeln('<info>Updating a </info>language');
            $this->commandBus()->dispatch(new UpdateLanguageNameCommand($languageId, $name));

            $output->writeln(
                '<info>The language with id </info>"'.$languageId.'"<info> has been successfully updated.</info>'
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
