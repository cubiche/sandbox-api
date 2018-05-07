<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Security\User;

use AppBundle\Command\Core\Command;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\Role\ReadModel\Query\FindOneRoleByName;
use Sandbox\Security\Domain\User\Command\CreateUserCommand;
use Sandbox\Security\Domain\User\UserId;
use Cubiche\Core\Validator\Exception\ValidationException;
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
            ->setName('app:user-create')
            ->addArgument('username', InputArgument::REQUIRED, 'The username.')
            ->addArgument('password', InputArgument::REQUIRED, 'The user password.')
            ->addArgument('email', InputArgument::REQUIRED, 'The user email.')
            ->addArgument('roles', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The user roles.')
            ->addOption('verify', null, InputOption::VALUE_NONE, 'The user should be verification by email?')
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
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
            $output->writeln('<info>Creating a new </info>user');

            $userId = UserId::next()->toNative();
            $this->commandBus()->dispatch(
                new CreateUserCommand(
                    $userId,
                    $input->getArgument('username'),
                    $input->getArgument('password'),
                    $input->getArgument('email'),
                    $this->getRolesId($input->getArgument('roles')),
                    $input->getOption('verify')
                )
            );

            $output->writeln(
                '<info>A new user with id </info>"'.$userId.'"<info> has been successfully created.</info>'
            );
        } catch (NotFoundException $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        } catch (ValidationException $e) {
            $this->printValidationErrors($e, $output);
        }
    }

    /**
     * @param array $roles
     *
     * @return Role
     */
    protected function getRolesId(array $roles)
    {
        $rolesId = array();
        foreach ($roles as $name) {
            $role = $this->queryBus()->dispatch(new FindOneRoleByName($name));
            if ($role === null) {
                throw new NotFoundException(sprintf(
                    'There is no role with name: %s',
                    $name
                ));
            }

            $rolesId[] = $role->roleId()->toNative();
        }

        return $rolesId;
    }
}
