<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language\ReadModel\Projection;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\System\Domain\Language\Event\LanguageNameWasUpdated;
use Sandbox\System\Domain\Language\Event\LanguageWasCreated;
use Sandbox\System\Domain\Language\Event\LanguageWasDisabled;
use Sandbox\System\Domain\Language\Event\LanguageWasEnabled;
use Sandbox\System\Domain\Language\LanguageId;
use Sandbox\System\Domain\Language\ReadModel\Language;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * LanguageProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageProjector implements DomainEventSubscriberInterface
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
     * @param LanguageWasCreated $event
     */
    public function whenLanguageWasCreated(LanguageWasCreated $event)
    {
        $readModel = new Language(
            $event->languageId(),
            $event->name(),
            $event->code()
        );

        $this->repository->persist($readModel);
    }

    /**
     * @param LanguageNameWasUpdated $event
     */
    public function whenLanguageNameWasUpdated(LanguageNameWasUpdated $event)
    {
        $readModel = $this->findOr404($event->languageId());
        $readModel->setName($event->name());

        $this->repository->persist($readModel);
    }

    /**
     * @param LanguageWasDisabled $event
     */
    public function whenLanguageWasDisabled(LanguageWasDisabled $event)
    {
        $readModel = $this->findOr404($event->languageId());
        $readModel->disable();

        $this->repository->persist($readModel);
    }

    /**
     * @param LanguageWasEnabled $event
     */
    public function whenLanguageWasEnabled(LanguageWasEnabled $event)
    {
        $readModel = $this->findOr404($event->languageId());
        $readModel->enable();

        $this->repository->persist($readModel);
    }

    /**
     * @param LanguageId $languageId
     *
     * @return Language
     */
    private function findOr404(LanguageId $languageId)
    {
        /** @var Language $language */
        $language = $this->repository->get($languageId);
        if ($language === null) {
            throw new NotFoundException(sprintf(
                'There is no language with id: %s',
                $languageId
            ));
        }

        return $language;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            LanguageWasCreated::class => array('whenLanguageWasCreated', 250),
            LanguageNameWasUpdated::class => array('whenLanguageNameWasUpdated', 250),
            LanguageWasDisabled::class => array('whenLanguageWasDisabled', 250),
            LanguageWasEnabled::class => array('whenLanguageWasEnabled', 250),
        );
    }
}
