<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Mailer\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * SendEmailCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SendEmailCommand extends Command
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $recipients;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $attachments;

    /**
     * SendEmailCommand constructor.
     *
     * @param string $code
     * @param array  $recipients
     * @param array  $data
     * @param array  $attachments
     */
    public function __construct(
        $code,
        array $recipients,
        array $data = [],
        array $attachments = []
    ) {
        $this->code = $code;
        $this->recipients = $recipients;
        $this->data = $data;
        $this->attachments = $attachments;
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
    public function recipients()
    {
        return $this->recipients;
    }

    /**
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function attachments()
    {
        return $this->attachments;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('code', Assertion::string()->notBlank());
        $classMetadata->addPropertyConstraint(
            'recipients',
            Assertion::isArray()->each(null, Assertion::email()->notBlank())
        );
    }
}
