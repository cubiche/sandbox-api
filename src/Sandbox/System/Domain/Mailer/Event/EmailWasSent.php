<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Mailer\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * EmailWasSent class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EmailWasSent extends DomainEvent
{
    /**
     * @var StringLiteral
     */
    protected $code;

    /**
     * @var StringLiteral
     */
    protected $senderName;

    /**
     * @var EmailAddress
     */
    protected $senderAddress;

    /**
     * @var array
     */
    protected $recipients;

    /**
     * EmailWasSent constructor.
     *
     * @param StringLiteral $code
     * @param StringLiteral $senderName
     * @param EmailAddress  $senderAddress
     * @param array         $recipients
     */
    public function __construct(
        StringLiteral $code,
        StringLiteral $senderName,
        EmailAddress $senderAddress,
        array $recipients
    ) {
        $this->code = $code;
        $this->senderName = $senderName;
        $this->senderAddress = $senderAddress;
        $this->recipients = $recipients;
    }

    /**
     * @return StringLiteral
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return StringLiteral
     */
    public function senderName()
    {
        return $this->senderName;
    }

    /**
     * @return EmailAddress
     */
    public function senderAddress()
    {
        return $this->senderAddress;
    }

    /**
     * @return array
     */
    public function recipients()
    {
        return $this->recipients;
    }
}
