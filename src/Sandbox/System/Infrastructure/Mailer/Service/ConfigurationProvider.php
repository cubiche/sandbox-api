<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Infrastructure\Mailer\Service;

use Sandbox\System\Domain\Mailer\Service\EmailFactoryInterface;
use Sandbox\System\Domain\Mailer\Service\ProviderInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * ConfigurationProvider class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ConfigurationProvider implements ProviderInterface
{
    /**
     * @var EmailFactoryInterface
     */
    protected $factory;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var string
     */
    protected $senderAddress;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * ConfigurationProvider constructor.
     *
     * @param EmailFactoryInterface $factory
     * @param string                $senderName
     * @param string                $senderAddress
     * @param array                 $configuration
     */
    public function __construct(EmailFactoryInterface $factory, $senderName, $senderAddress, array $configuration)
    {
        $this->factory = $factory;
        $this->senderName = $senderName;
        $this->senderAddress = $senderAddress;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(StringLiteral $code)
    {
        if (!array_key_exists($code->toNative(), $this->configuration)) {
            throw new \InvalidArgumentException(sprintf('Email with code "%s" does not exist!', $code));
        }

        $configuration = $this->configuration[$code->toNative()];

        $subject = array_key_exists('subject', $configuration) ? $configuration['subject'] : null;
        $content = array_key_exists('content', $configuration) ? $configuration['content'] : null;
        $template = array_key_exists('template', $configuration) ? $configuration['template'] : null;

        if ($subject === null && $content === null && $template === null) {
            throw new \InvalidArgumentException(sprintf(
                'Email with code "%s" has to define at least one of theses fields [subject, content, template]',
                $code
            ));
        }

        $email = $this->factory->create(
            $code->toNative(),
            $this->senderName,
            $this->senderAddress,
            $subject,
            $content,
            $template
        );

        if (isset($configuration['enabled']) && false === $configuration['enabled']) {
            $email->disable();
        }

        return $email;
    }
}
