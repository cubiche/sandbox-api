<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\Event;

use Sandbox\System\Domain\Currency\CurrencyId;
use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * CurrencyNameWasUpdated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyNameWasUpdated extends DomainEvent
{
    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * CurrencyNameWasUpdated constructor.
     *
     * @param CurrencyId        $currencyId
     * @param LocalizableString $name
     */
    public function __construct(CurrencyId $currencyId, LocalizableString $name)
    {
        parent::__construct($currencyId);

        $this->name = $name;
    }

    /**
     * @return CurrencyId
     */
    public function currencyId()
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
