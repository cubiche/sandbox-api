<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * CreateCountryCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateCountryCommand extends Command
{
    /**
     * @var string
     */
    protected $countryId;

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
     * CreateCountryCommand constructor.
     *
     * @param string $countryId
     * @param array  $name
     */
    public function __construct($countryId, $code, $name, $defaultLocale)
    {
        $this->countryId = $countryId;
        $this->code = $code;
        $this->name = $name;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string
     */
    public function countryId()
    {
        return $this->countryId;
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
        $classMetadata->addPropertyConstraint('countryId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('code', Assertion::uniqueCountryCode());
        $classMetadata->addPropertyConstraint('defaultLocale', Assertion::languageCode()->notBlank());
        $classMetadata->addPropertyConstraint(
            'name',
            Assertion::isArray()->each(Assertion::localeCode()->notBlank(), Assertion::string()->notBlank())
        );
    }
}
