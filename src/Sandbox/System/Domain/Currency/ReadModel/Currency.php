<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\ReadModel;

use Sandbox\System\Domain\Currency\CurrencyId;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\StringLiteral;

/**
 * Currency class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Currency extends Entity implements ReadModelInterface
{
    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * @var StringLiteral
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
     * @param StringLiteral     $code
     * @param StringLiteral     $symbol
     */
    public function __construct(
        CurrencyId $currencyId,
        LocalizableString $name,
        StringLiteral $code,
        StringLiteral $symbol
    ) {
        parent::__construct($currencyId);

        $this->name = $name;
        $this->code = $code;
        $this->symbol = $symbol;
        $this->enabled = true;
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
    public function setName(LocalizableString $name)
    {
        $this->name = $name;
    }

    /**
     * @return StringLiteral
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @param StringLiteral $code
     */
    public function setCode(StringLiteral $code)
    {
        $this->code = $code;
    }

    /**
     * @return StringLiteral
     */
    public function symbol()
    {
        return $this->symbol;
    }

    /**
     * @param StringLiteral $symbol
     */
    public function setSymbol(StringLiteral $symbol)
    {
        $this->symbol = $symbol;
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
        $this->enabled = true;
    }

    /**
     * Disable the currency.
     */
    public function disable()
    {
        $this->enabled = false;
    }
}
