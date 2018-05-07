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

use Sandbox\System\Domain\Mailer\Email;
use Sandbox\System\Domain\Mailer\Service\RendererInterface;

/**
 * TwigRenderer class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TwigRenderer implements RendererInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Email $email
     * @param array $data
     *
     * @return string
     */
    public function subject(Email $email, array $data = [])
    {
        if (null !== $email->template()) {
            return $this->withTemplate($email->template()->toNative(), 'subject', $data);
        }

        return $this->withoutTemplate($email->subject()->toNative(), $data);
    }

    /**
     * @param Email $email
     * @param array $data
     *
     * @return string
     */
    public function body(Email $email, array $data = [])
    {
        if (null !== $email->template()) {
            return $this->withTemplate($email->template()->toNative(), 'body', $data);
        }

        return $this->withoutTemplate($email->content()->toNative(), $data);
    }

    /**
     * @param string $templateName
     * @param string $blockName
     * @param array  $data
     *
     * @return string
     */
    protected function withTemplate($templateName, $blockName, array $data)
    {
        $data = $this->twig->mergeGlobals($data);

        /** @var \Twig_Template $template */
        $template = $this->twig->loadTemplate($templateName);

        return $template->renderBlock($blockName, $data);
    }

    /**
     * @param string $content
     * @param array  $data
     *
     * @return RenderedEmail
     */
    protected function withoutTemplate($content, array $data)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Array([]));

        $template = $twig->createTemplate($content);

        return $template->render($data);
    }
}
