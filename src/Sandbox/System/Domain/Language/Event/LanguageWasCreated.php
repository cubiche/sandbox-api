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
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * LanguageWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageWasCreated extends DomainEvent
{
    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * @var LanguageCode
     */
    protected $code;

    /**
     * LanguageWasCreated constructor.
     *
     * @param LanguageId        $languageId
     * @param LocalizableString $name
     * @param LanguageCode      $code
     */
    public function __construct(
        LanguageId $languageId,
        LocalizableString $name,
        LanguageCode $code
    ) {
        parent::__construct($languageId);

        $this->name = $name;
        $this->code = $code;
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

    /**
     * @return LanguageCode
     */
    public function code()
    {
        return $this->code;
    }
}
