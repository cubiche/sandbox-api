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

/**
 * UpdateCurrencyNameCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UpdateCurrencyNameCommand extends Command
{
    /**
     * @var string
     */
    protected $currencyId;

    /**
     * @var array
     */
    protected $name;

    /**
     * UpdateCurrencyNameCommand constructor.
     *
     * @param string $currencyId
     * @param array  $name
     */
    public function __construct($currencyId, array $name)
    {
        $this->currencyId = $currencyId;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function currencyId()
    {
        return $this->currencyId;
    }

    /**
     * @return array
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('currencyId', Assertion::uuid()->notBlank());

        $classMetadata->addPropertyConstraint(
            'name',
            Assertion::isArray()->each(
                Assertion::localeCode()->notBlank(),
                Assertion::string()->notBlank()
            )
        );
    }
}
