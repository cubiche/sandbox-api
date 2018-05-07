<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Infrastructure\Permission\Service;

use Cubiche\Core\Collections\ArrayCollection\SortedArraySet;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * PermissionRepository class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PermissionRepository implements QueryRepositoryInterface
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $classMetadataFactory;

    /**
     * @var SortedArraySet
     */
    protected $collection;

    /**
     * PermissionRepository constructor.
     *
     * @param ClassMetadataFactoryInterface $classMetadataFactory
     */
    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
        $this->collection = new SortedArraySet(array(
            'app',
            'app.conference',
            'app.order',
            'app.seats_availability',
            'app.permission',
            'app.role',
            'app.user',
            'app.country',
            'app.currency',
            'app.language',
            'app.mailer',
        ));

        $this->init();
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return $this->collection->sorted($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        return $this->findOne(Criteria::method('id')->eq($id));
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return $this->collection->find($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return $this->collection->findOne($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function persist(ReadModelInterface $element)
    {
        $this->collection->add($element);
    }

    /**
     * {@inheritdoc}
     */
    public function persistAll($elements)
    {
        foreach ($elements as $element) {
            $this->persist($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ReadModelInterface $element)
    {
        $this->collection->remove($element);
    }

    /**
     * {@inheritdoc}
     */
    private function init()
    {
        /** @var ClassMetadata $classMetadata */
        foreach ($this->classMetadataFactory->getAllMetadata() as $classMetadata) {
            $classPermissions = $classMetadata->getMetadata('permissions') ?: array();
            $this->collection->addAll($classPermissions);

            foreach ($classMetadata->methodsMetadata() as $methodMetadata) {
                $methodPermissions = $methodMetadata->getMetadata('permissions') ?: array();

                $this->collection->addAll($methodPermissions);
            }
        }
    }
}
