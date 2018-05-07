<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;
use Cubiche\Domain\Localizable\LocalizableValueInterface;

/**
 * CreateLanguageCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateLanguageCommand extends Command
{
    /**
     * @var string
     */
    protected $languageId;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $name;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * CreateLanguageCommand constructor.
     *
     * @param string $languageId
     * @param string $code
     * @param array  $name
     * @param string $defaultLocale
     */
    public function __construct(
        $languageId,
        $code,
        array $name,
        $defaultLocale = LocalizableValueInterface::DEFAULT_LOCALE
    ) {
        $this->languageId = $languageId;
        $this->code = $code;
        $this->name = $name;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string
     */
    public function languageId()
    {
        return $this->languageId;
    }

    /**
     * @return string
     */
    public function code()
    {
        return $this->code;
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
        $classMetadata->addPropertyConstraint('languageId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('code', Assertion::uniqueLanguageCode());
        $classMetadata->addPropertyConstraint('defaultLocale', Assertion::localeCode()->notBlank());

        $classMetadata->addPropertyConstraint(
            'name',
            Assertion::isArray()->each(
                Assertion::localeCode()->notBlank(),
                Assertion::string()->notBlank()
            )
        );
    }
}
