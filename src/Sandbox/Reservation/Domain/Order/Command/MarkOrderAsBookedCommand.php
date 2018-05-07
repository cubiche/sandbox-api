<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Reservation\Domain\Order\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * MarkOrderAsBookedCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MarkOrderAsBookedCommand extends Command
{
    /**
     * @var string
     */
    protected $orderId;

    /**
     * MarkOrderAsBookedCommand constructor.
     *
     * @param string $orderId
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function orderId()
    {
        return $this->orderId;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('orderId', Assertion::uuid()->notBlank());
    }
}
