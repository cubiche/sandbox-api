<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Conference\Domain\ReadModel\Projection;

use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Sandbox\Conference\Domain\Event\ConferenceWasCreated;
use Sandbox\Conference\Domain\ReadModel\Conference;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\System\Domain\Country\ReadModel\Country;
use Sandbox\System\Domain\Country\ReadModel\Query\FindOneCountryByCode;

/**
 * ConferenceProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * @var QueryRepositoryInterface
     */
    protected $queryBus;

    /**
     * ConferenceProjector constructor.
     *
     * @param QueryRepositoryInterface $repository
     * @param QueryBus                 $queryBus
     */
    public function __construct(QueryRepositoryInterface $repository, QueryBus $queryBus)
    {
        $this->repository = $repository;
        $this->queryBus = $queryBus;
    }

    /**
     * @param ConferenceWasCreated $event
     */
    public function whenConferenceWasCreated(ConferenceWasCreated $event)
    {
        $country = $this->findCountryOr404($event->countryCode()->toNative());

        $readModel = new Conference(
            $event->conferenceId(),
            $event->name(),
            $event->city(),
            $country->name(),
            $event->availableTickets(),
            $event->price(),
            $event->date()
        );

        $this->repository->persist($readModel);
    }

    /**
     * @param string $countryCode
     *
     * @return Country
     */
    private function findCountryOr404($countryCode)
    {
        /** @var Country $country */
        $country = $this->queryBus->dispatch(new FindOneCountryByCode($countryCode));
        if ($country === null) {
            throw new NotFoundException(sprintf(
                'There is no country with code: %s',
                $countryCode
            ));
        }

        return $country;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ConferenceWasCreated::class => array('whenConferenceWasCreated', 250),
        );
    }
}
