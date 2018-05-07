<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Domain\Tests\Units;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Domain\Repository\InMemory\InMemoryQueryRepository;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * SettingQueryRepository trait.
 *
 * Generated by TestGenerator on 2017-09-13 at 12:50:27.
 */
trait SettingQueryRepositoryTrait
{
    /**
     * @var ArrayHashMap
     */
    private $queryRepositories;

    /**
     * @param $className
     *
     * @return QueryRepositoryInterface
     */
    protected function queryRepository($className)
    {
        if ($this->queryRepositories === null) {
            $this->queryRepositories = new ArrayHashMap();
        }

        if (!$this->queryRepositories->containsKey($className)) {
            $this->queryRepositories->set($className, new InMemoryQueryRepository());
        }

        return $this->queryRepositories->get($className);
    }
}
