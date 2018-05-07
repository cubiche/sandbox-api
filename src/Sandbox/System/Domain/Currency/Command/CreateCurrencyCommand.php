<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;
use Cubiche\Domain\Localizable\LocalizableValueInterface;

/**
 * CreateCurrencyCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateCurrencyCommand extends Command
{
    /**
     * @var string
     */
    protected $currencyId;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var array
     */
    protected $name;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * CreateCurrencyCommand constructor.
     *
     * @param string $currencyId
     * @param string $code
     * @param string $symbol
     * @param array  $name
     * @param string $defaultLocale
     */
    public function __construct(
        $currencyId,
        $code,
        $symbol,
        array $name,
        $defaultLocale = LocalizableValueInterface::DEFAULT_LOCALE
    ) {
        $this->currencyId = $currencyId;
        $this->code = $code;
        $this->symbol = $symbol;
        $this->name = $name;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string
     */
    public function currencyId()
    {
        return $this->currencyId;
    }

    /**
     * @return string
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function symbol()
    {
        return $this->symbol;
    }

    /**
     * @return array
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function defaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('currencyId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('code', Assertion::uniqueCurrencyCode());
        $classMetadata->addPropertyConstraint('symbol', Assertion::string()->length(1)->notBlank());
        $classMetadata->addPropertyConstraint('defaultLocale', Assertion::languageCode()->notBlank());

        $classMetadata->addPropertyConstraint(
            'name',
            Assertion::isArray()->each(
                Assertion::localeCode()->notBlank(),
                Assertion::string()->notBlank()
            )
        );
    }
}
