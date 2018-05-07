<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\ReadModel\Projection;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\System\Domain\Currency\CurrencyId;
use Sandbox\System\Domain\Currency\Event\CurrencyNameWasUpdated;
use Sandbox\System\Domain\Currency\Event\CurrencyWasCreated;
use Sandbox\System\Domain\Currency\Event\CurrencyWasDisabled;
use Sandbox\System\Domain\Currency\Event\CurrencyWasEnabled;
use Sandbox\System\Domain\Currency\ReadModel\Currency;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * CurrencyProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * Projector constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CurrencyWasCreated $event
     */
    public function whenCurrencyWasCreated(CurrencyWasCreated $event)
    {
        $readModel = new Currency(
            $event->currencyId(),
            $event->name(),
            StringLiteral::fromNative($event->code()->toNative()),
            $event->symbol()
        );

        $this->repository->persist($readModel);
    }

    /**
     * @param CurrencyNameWasUpdated $event
     */
    public function whenCurrencyNameWasUpdated(CurrencyNameWasUpdated $event)
    {
        $readModel = $this->findOr404($event->currencyId());
        $readModel->setName($event->name());

        $this->repository->persist($readModel);
    }

    /**
     * @param CurrencyWasDisabled $event
     */
    public function whenCurrencyWasDisabled(CurrencyWasDisabled $event)
    {
        $readModel = $this->findOr404($event->currencyId());
        $readModel->disable();

        $this->repository->persist($readModel);
    }

    /**
     * @param CurrencyWasEnabled $event
     */
    public function whenCurrencyWasEnabled(CurrencyWasEnabled $event)
    {
        $readModel = $this->findOr404($event->currencyId());
        $readModel->enable();

        $this->repository->persist($readModel);
    }

    /**
     * @param CurrencyId $currencyId
     *
     * @return Currency
     */
    private function findOr404(CurrencyId $currencyId)
    {
        /** @var Currency $currency */
        $currency = $this->repository->get($currencyId);
        if ($currency === null) {
            throw new NotFoundException(sprintf(
                'There is no currency with id: %s',
                $currencyId
            ));
        }

        return $currency;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            CurrencyWasCreated::class => array('whenCurrencyWasCreated', 250),
            CurrencyNameWasUpdated::class => array('whenCurrencyNameWasUpdated', 250),
            CurrencyWasDisabled::class => array('whenCurrencyWasDisabled', 250),
            CurrencyWasEnabled::class => array('whenCurrencyWasEnabled', 250),
        );
    }
}
