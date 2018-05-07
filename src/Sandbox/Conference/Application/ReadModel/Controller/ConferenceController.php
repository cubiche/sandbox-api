<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Application\ReadModel\Controller;

use Cubiche\Core\Collections\CollectionInterface;
use Sandbox\Conference\Domain\ReadModel\Conference;
use Sandbox\Conference\Domain\ReadModel\Query\FindAllConferences;
use Sandbox\Conference\Domain\ReadModel\Query\FindOneConferenceById;
use Sandbox\Core\Application\Controller\QueryController;

/**
 * ConferenceController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceController extends QueryController
{
    /**
     * @return CollectionInterface|Conference[]
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(new FindAllConferences());
    }

    /**
     * @param string $conferenceId
     *
     * @return Conference|null
     */
    public function findOneByIdAction($conferenceId)
    {
        return $this->queryBus()->dispatch(new FindOneConferenceById($conferenceId));
    }
}
