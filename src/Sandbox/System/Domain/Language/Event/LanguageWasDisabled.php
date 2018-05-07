<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language\Event;

use Sandbox\System\Domain\Language\LanguageId;
use Cubiche\Domain\EventSourcing\DomainEvent;

/**
 * LanguageWasDisabled class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageWasDisabled extends DomainEvent
{
    /**
     * LanguageWasDisabled constructor.
     *
     * @param LanguageId $languageId
     */
    public function __construct(LanguageId $languageId)
    {
        parent::__construct($languageId);
    }

    /**
     * @return LanguageId
     */
    public function languageId()
    {
        return $this->aggregateId();
    }
}
