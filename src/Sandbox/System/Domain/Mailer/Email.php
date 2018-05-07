<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Mailer;

use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * Email class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Email
{
    /**
     * @var StringLiteral
     */
    protected $code;

    /**
     * @var StringLiteral
     */
    protected $subject;

    /**
     * @var StringLiteral
     */
    protected $content;

    /**
     * @var StringLiteral
     */
    protected $senderName;

    /**
     * @var EmailAddress
     */
    protected $senderAddress;

    /**
     * @var StringLiteral
     */
    protected $template;

    /**
     * @var bool
     */
    protected $enable;

    /**
     * Email constructor.
     *
     * @param StringLiteral      $code
     * @param StringLiteral      $senderName
     * @param EmailAddress       $senderAddress
     * @param StringLiteral|null $subject
     * @param StringLiteral|null $content
     * @param StringLiteral|null $template
     */
    public function __construct(
        StringLiteral $code,
        StringLiteral $senderName,
        EmailAddress $senderAddress,
        StringLiteral $subject = null,
        StringLiteral $content = null,
        StringLiteral $template = null
    ) {
        $this->code = $code;
        $this->senderName = $senderName;
        $this->senderAddress = $senderAddress;
        $this->subject = $subject;
        $this->content = $content;
        $this->template = $template;
        $this->enabled = true;
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
    public function subject()
    {
        return $this->subject;
    }

    /**
     * @return StringLiteral
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @return StringLiteral
     */
    public function template()
    {
        return $this->template;
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
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable the email.
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disable the email.
     */
    public function disable()
    {
        $this->enabled = false;
    }
}
