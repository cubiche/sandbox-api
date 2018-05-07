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
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * LanguageNameWasUpdated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageNameWasUpdated extends DomainEvent
{
    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * LanguageNameWasUpdated constructor.
     *
     * @param LanguageId        $languageId
     * @param LocalizableString $name
     */
    public function __construct(
        LanguageId $languageId,
        LocalizableString $name
    ) {
        parent::__construct($languageId);

        $this->name = $name;
    }

    /**
     * @return LanguageId
     */
    public function languageId()
    {
        return $this->aggregateId();
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }
}
