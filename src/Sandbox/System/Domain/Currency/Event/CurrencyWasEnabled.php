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

/**
 * CurrencyWasEnabled class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyWasEnabled extends DomainEvent
{
    /**
     * CurrencyWasEnabled constructor.
     *
     * @param CurrencyId $currencyId
     */
    public function __construct(CurrencyId $currencyId)
    {
        parent::__construct($currencyId);
    }

    /**
     * @return CurrencyId
     */
    public function currencyId()
    {
        return $this->aggregateId();
    }
}
