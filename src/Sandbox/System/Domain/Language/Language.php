<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language;

use Sandbox\System\Domain\Language\Event\LanguageNameWasUpdated;
use Sandbox\System\Domain\Language\Event\LanguageWasCreated;
use Sandbox\System\Domain\Language\Event\LanguageWasDisabled;
use Sandbox\System\Domain\Language\Event\LanguageWasEnabled;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * Language class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Language extends AggregateRoot
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
     * @var bool
     */
    protected $enabled;

    /**
     * Language constructor.
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

        $this->recordAndApplyEvent(
            new LanguageWasCreated($languageId, $name, $code)
        );
    }

    /**
     * @return LanguageId
     */
    public function languageId()
    {
        return $this->id;
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param LocalizableString $name
     */
    public function updateName(LocalizableString $name)
    {
        $this->recordAndApplyEvent(
            new LanguageNameWasUpdated($this->languageId(), $name)
        );
    }

    /**
     * @return LanguageCode
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable the language.
     */
    public function enable()
    {
        $this->recordAndApplyEvent(
            new LanguageWasEnabled($this->languageId())
        );
    }

    /**
     * Disable the language.
     */
    public function disable()
    {
        $this->recordAndApplyEvent(
            new LanguageWasDisabled($this->languageId())
        );
    }

    /**
     * @param LanguageWasCreated $event
     */
    protected function applyLanguageWasCreated(LanguageWasCreated $event)
    {
        $this->name = $event->name();
        $this->code = $event->code();
        $this->enabled = true;
    }

    /**
     * @param LanguageNameWasUpdated $event
     */
    protected function applyLanguageNameWasUpdated(LanguageNameWasUpdated $event)
    {
        $this->name = $event->name();
    }

    /**
     * @param LanguageWasEnabled $event
     */
    protected function applyLanguageWasEnabled(LanguageWasEnabled $event)
    {
        $this->enabled = true;
    }

    /**
     * @param LanguageWasDisabled $event
     */
    protected function applyLanguageWasDisabled(LanguageWasDisabled $event)
    {
        $this->enabled = false;
    }
}
