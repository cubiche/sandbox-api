<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Core;

use Cubiche\Core\Validator\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * EnvironmentAwareCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class Command extends ContainerAwareCommand
{
    /**
     * @param $command
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     *
     * @throws \Exception
     */
    protected function runCommand($command, InputInterface $input, OutputInterface $output)
    {
        $this
            ->getApplication()
            ->find($command)
            ->run($input, $output)
        ;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->getContainer()->getParameter($name);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * @return \Cubiche\Core\Cqrs\Command\CommandBus
     */
    protected function commandBus()
    {
        return $this->get('cubiche.command_bus');
    }

    /**
     * @return \Cubiche\Core\Cqrs\Query\QueryBus
     */
    protected function queryBus()
    {
        return $this->get('cubiche.query_bus');
    }

    /**
     * @param ValidationException $e
     * @param OutputInterface     $output
     */
    protected function printValidationErrors(ValidationException $e, OutputInterface $output)
    {
        $i = 1;
        foreach ($e->getErrorExceptions() as $error) {
            $output->writeln(
                '<error>'.sprintf('%d) %s: %s', $i++, $error->getPropertyPath(), $error->getMessage()).'</error>'
            );
        }
    }
}
