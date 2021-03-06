<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Domain\Tests\Units\Event;

use Cubiche\Core\Validator\Validator;
use Cubiche\Domain\EventSourcing\DomainEventInterface;

/**
 * EventTestTrait class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait EventTestTrait
{
    /**
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function validatorProvider()
    {
        return array();
    }

    /**
     * @param array $arguments
     *
     * @return DomainEventInterface
     */
    protected function createEvent(array $arguments)
    {
        $reflection = new \ReflectionClass($this->testedClass->getClass());

        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(DomainEventInterface::class)
        ;
    }

    /**
     * Test create.
     */
    public function testCreateEvent()
    {
        $event = $this->createEvent($this->getArguments());
        $reflection = new \ReflectionClass(get_class($event));

        $parameters = $reflection->getConstructor()->getParameters();

        if ($reflection->hasMethod('id')) {
            $this
                ->given($id = $event->aggregateId())
                ->then()
                ->variable($event->id())
                ->isEqualTo($id)
            ;
        }

        foreach ($parameters as $parameter) {
            $methodName = $parameter->getName();

            // check that there is a method for every constructor argument
            // and returns the same value passed in the constructor
            if ($reflection->hasProperty($parameter->getName())) {
                $proterty = $reflection->getProperty($parameter->getName());
                $proterty->setAccessible(true);
                $protertyValue = $proterty->getValue($event);
            } else {
                $protertyValue = $event->aggregateId();
            }

            $this
                ->given($value = $event->{$methodName}())
                ->then()
                    ->variable($value)
                        ->isEqualTo($protertyValue)
            ;
        }
    }

    /**
     * Test validator.
     */
    public function testValidateEvent()
    {
        foreach ($this->validatorProvider() as $provider) {
            $this
                ->given($event = $this->createEvent($provider['arguments']))
                ->then()
                    ->boolean(Validator::validate($event))
                        ->isEqualTo($provider['assert'])
            ;
        }
    }
}
