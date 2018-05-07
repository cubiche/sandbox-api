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

use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\CurrencyId;
use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\System\StringLiteral;

/**
 * CurrencyWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyWasCreated extends DomainEvent
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
     * CurrencyWasCreated constructor.
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

        $this->name = $name;
        $this->code = $code;
        $this->symbol = $symbol;
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
}
