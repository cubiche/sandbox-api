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
 * DisableCurrencyCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class DisableCurrencyCommand extends Command
{
    /**
     * @var string
     */
    protected $currencyId;

    /**
     * DisableCurrencyCommand constructor.
     *
     * @param string $currencyId
     */
    public function __construct($currencyId)
    {
        $this->currencyId = $currencyId;
    }

    /**
     * @return string
     */
    public function currencyId()
    {
        return $this->currencyId;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('currencyId', Assertion::uuid()->notBlank());
    }
}
