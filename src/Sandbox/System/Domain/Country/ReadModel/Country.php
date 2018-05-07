<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country\ReadModel;

use Sandbox\System\Domain\Country\CountryId;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\StringLiteral;

/**
 * Country class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Country extends Entity implements ReadModelInterface
{
    /**
     * @var StringLiteral
     */
    private $code;

    /**
     * @var LocalizableString
     */
    private $name;

    public function __construct(CountryId $countryId, StringLiteral $code, LocalizableString $name)
    {
        parent::__construct($countryId);

        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return CountryId
     */
    public function countryId()
    {
        return $this->id;
    }

    /**
     * @return StringLiteral
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }
}
