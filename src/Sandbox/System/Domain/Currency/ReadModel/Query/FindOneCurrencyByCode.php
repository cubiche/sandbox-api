<?php

/** * This file is part of the Sandbox application.
 * Copyright (c) Cubiche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency\ReadModel\Query;

use Cubiche\Core\Cqrs\Query\Query;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * FindOneCurrencyByCode class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneCurrencyByCode extends Query
{
    /**
     * @var string
     */
    protected $code;

    /**
     * FindOneCurrencyByCode constructor.
     *
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('code', Assertion::currencyCode()->notBlank());
    }
}
