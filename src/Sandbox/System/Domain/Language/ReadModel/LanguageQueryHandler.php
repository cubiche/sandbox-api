<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language\ReadModel;

use Cubiche\Core\Collections\CollectionInterface;
use Sandbox\System\Domain\Language\ReadModel\Query\FindAllLanguages;
use Sandbox\System\Domain\Language\ReadModel\Query\FindOneLanguageByCode;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * LanguageQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * LanguageQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindAllLanguages $query
     *
     * @return CollectionInterface|Language[]
     */
    public function findAllLanguages(FindAllLanguages $query)
    {
        return $this->repository->getIterator();
    }

    /**
     * @param FindOneLanguageByCode $query
     *
     * @return Language|null
     */
    public function findOneLanguageByCode(FindOneLanguageByCode $query)
    {
        return $this->repository->findOne(
            Criteria::property('code')->eq(LanguageCode::fromNative($query->code()))
        );
    }
}
