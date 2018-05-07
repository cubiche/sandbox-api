<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Security\Role;

use AppBundle\Command\Core\Command;
use Cubiche\Core\Validator\Exception\ValidationException;
use Sandbox\Security\Domain\Role\Command\CreateRoleCommand;
use Sandbox\Security\Domain\Role\RoleId;
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
            ->setName('app:role-create')
            ->addArgument('name', InputArgument::REQUIRED, 'Role name.')
            ->addArgument('permissions', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The role permissions.')
            ->setDescription('Creates a new role.')
            ->setHelp('This command allows you to create a role...')
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
        try {
            $output->writeln('<info>Creating a new role</info>');

            $roleId = RoleId::next()->toNative();
            $this->commandBus()->dispatch(
                new CreateRoleCommand(
                    $roleId,
                    $input->getArgument('name'),
                    $input->getArgument('permissions')
                )
            );

            $output->writeln(
                '<info>A new role with id </info>"'.$roleId.
                '"<info> has been successfully created.</info>'
            );
        } catch (ValidationException $e) {
            $this->printValidationErrors($e, $output);
        }
    }
}
