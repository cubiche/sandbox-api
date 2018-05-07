<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Infrastructure\Aspect;

use Sandbox\Core\Application\Event\AfterActionCallEvent;
use Sandbox\Core\Application\Event\BeforeActionCallEvent;
use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;

/**
 * AccessControlAspect class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AccessControlAspect implements Aspect
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $classMetadataFactory;

    /**
     * AccessControlAspect constructor.
     *
     * @param ClassMetadataFactoryInterface $classMetadataFactory
     */
    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
    }

    /**
     * Writes a log info before method execution.
     *
     * @param MethodInvocation $invocation
     * @Go\Lang\Annotation\Around("execution(public **->*Action(*)) && (within(Sandbox\Core\Application\Controller\CommandController+) || within(Sandbox\Core\Application\Controller\QueryController+))")
     *
     * @return mixed
     */
    public function aroundAction(MethodInvocation $invocation)
    {
        $permissions = array();
        $classMetadata = $this->classMetadataFactory->getMetadataFor(
            get_class($invocation->getThis())
        );

        if ($classMetadata !== null) {
            $classPermissions = $classMetadata->getMetadata('permissions') ?: array();
            $methodMetadata = $classMetadata->methodMetadata(
                $invocation->getMethod()->name
            );

            $methodPermissions = array();
            if ($methodMetadata !== null) {
                $methodPermissions = $methodMetadata->getMetadata('permissions') ?: array();
            }

            $permissions = array_merge($classPermissions, $methodPermissions);
        }

        $reflectionMethod = new \ReflectionMethod(
            get_class($invocation->getThis()),
            $invocation->getMethod()->getName()
        );

        DomainEventPublisher::publish(
            new BeforeActionCallEvent(
                $reflectionMethod,
                $invocation->getArguments(),
                $permissions
            )
        );

        $result = $invocation->proceed();

        DomainEventPublisher::publish(
            new AfterActionCallEvent(
                $reflectionMethod,
                $invocation->getArguments(),
                $permissions
            )
        );

        return $result;
    }
}
