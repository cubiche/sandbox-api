<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Application\Controller;

use Cubiche\Core\Cqrs\Query\QueryBus;

/**
 * QueryController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class QueryController
{
    /**
     * @var QueryBus
     */
    protected $queryBus;

    /**
     * QueryController constructor.
     *
     * @param QueryBus $queryBus
     */
    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @return QueryBus
     */
    public function queryBus()
    {
        return $this->queryBus;
    }
}
