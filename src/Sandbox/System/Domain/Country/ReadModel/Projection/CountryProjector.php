<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country\ReadModel\Projection;

use Sandbox\System\Domain\Country\Event\CountryWasCreated;
use Sandbox\System\Domain\Country\ReadModel\Country;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * CountryProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $queryRepository;

    /**
     * Projector constructor.
     *
     * @param QueryRepositoryInterface $queryRepository
     */
    public function __construct(QueryRepositoryInterface $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    public function whenCountryWasCreated(CountryWasCreated $event)
    {
        $country = new Country(
            $event->countryId(),
            $event->code(),
            $event->name()
        );
        $this->queryRepository->persist($country);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            CountryWasCreated::class => array('whenCountryWasCreated'),
        );
    }
}
