<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language\ReadModel;

use Sandbox\System\Domain\Language\LanguageId;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Model\Entity;

/**
 * Language class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Language extends Entity implements ReadModelInterface
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

        $this->name = $name;
        $this->code = $code;
        $this->enabled = true;
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
    public function setName(LocalizableString $name)
    {
        $this->name = $name;
    }

    /**
     * @return LanguageCode
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @param LanguageCode $code
     */
    public function setCode(LanguageCode $code)
    {
        $this->code = $code;
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
