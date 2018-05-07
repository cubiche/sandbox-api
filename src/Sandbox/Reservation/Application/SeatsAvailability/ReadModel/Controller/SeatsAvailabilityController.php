<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Application\SeatsAvailability\ReadModel\Controller;

use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\Query\FindOneSeatsAvailabilityById;
use Sandbox\Reservation\Domain\SeatsAvailability\ReadModel\SeatsAvailability;

/**
 * SeatsAvailabilityController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityController extends QueryController
{
    /**
     * @param string $conferenceId
     *
     * @return SeatsAvailability|null
     */
    public function findOneByIdAction($conferenceId)
    {
        return $this->queryBus()->dispatch(new FindOneSeatsAvailabilityById($conferenceId));
    }
}
