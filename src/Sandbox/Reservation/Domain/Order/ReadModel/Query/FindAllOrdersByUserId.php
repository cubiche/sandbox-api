<?php

/**
 * This file is part of the Sandbox application.
 * Copyright (c) Cubiche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order\ReadModel\Query;

use Cubiche\Core\Cqrs\Query\Query;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * FindAllOrdersByUserId class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllOrdersByUserId extends Query
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * FindAllOrdersByUserId constructor.
     *
     * @param string $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('userId', Assertion::uuid()->notBlank());
    }
}
