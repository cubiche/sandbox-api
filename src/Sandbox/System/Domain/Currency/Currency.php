<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency;

use Sandbox\System\Domain\Currency\Event\CurrencyNameWasUpdated;
use Sandbox\System\Domain\Currency\Event\CurrencyWasCreated;
use Sandbox\System\Domain\Currency\Event\CurrencyWasDisabled;
use Sandbox\System\Domain\Currency\Event\CurrencyWasEnabled;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * Currency class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Currency extends AggregateRoot
{
    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * @var CurrencyCode
     */
    protected $code;

    /**
     * @var StringLiteral
     */
    protected $symbol;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * Currency constructor.
     *
     * @param CurrencyId        $currencyId
     * @param LocalizableString $name
     * @param CurrencyCode      $code
     * @param StringLiteral     $symbol
     */
    public function __construct(
        CurrencyId $currencyId,
        LocalizableString $name,
        CurrencyCode $code,
        StringLiteral $symbol
    ) {
        parent::__construct($currencyId);

        $this->recordAndApplyEvent(
            new CurrencyWasCreated($currencyId, $name, $code, $symbol)
        );
    }

    /**
     * @return CurrencyId
     */
    public function currencyId()
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
            new CurrencyNameWasUpdated($this->currencyId(), $name)
        );
    }

    /**
     * @return CurrencyCode
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return StringLiteral
     */
    public function symbol()
    {
        return $this->symbol;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable the currency.
     */
    public function enable()
    {
        $this->recordAndApplyEvent(
            new CurrencyWasEnabled($this->currencyId())
        );
    }

    /**
     * Disable the currency.
     */
    public function disable()
    {
        $this->recordAndApplyEvent(
            new CurrencyWasDisabled($this->currencyId())
        );
    }

    /**
     * @param CurrencyWasCreated $event
     */
    protected function applyCurrencyWasCreated(CurrencyWasCreated $event)
    {
        $this->name = $event->name();
        $this->code = $event->code();
        $this->symbol = $event->symbol();
        $this->enabled = true;
    }

    /**
     * @param CurrencyNameWasUpdated $event
     */
    protected function applyCurrencyNameWasUpdated(CurrencyNameWasUpdated $event)
    {
        $this->name = $event->name();
    }

    /**
     * @param CurrencyWasEnabled $event
     */
    protected function applyCurrencyWasEnabled(CurrencyWasEnabled $event)
    {
        $this->enabled = true;
    }

    /**
     * @param CurrencyWasDisabled $event
     */
    protected function applyCurrencyWasDisabled(CurrencyWasDisabled $event)
    {
        $this->enabled = false;
    }
}
