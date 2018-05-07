<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\User\Service;

use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Core\Application\Service\TokenContextInterface;
use Sandbox\Security\Domain\User\Service\SecurityContextInterface;
use Sandbox\Security\Domain\User\UserId;

/**
 * SecurityContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SecurityContext implements SecurityContextInterface
{
    /**
     * @var TokenContextInterface
     */
    protected $tokenContext;

    /**
     * @var string
     */
    protected $jwt;

    /**
     * SecurityContext constructor.
     *
     * @param TokenContextInterface $tokenContext
     */
    public function __construct(TokenContextInterface $tokenContext)
    {
        $this->tokenContext = $tokenContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getJWT()
    {
        return $this->tokenContext->getJWT();
    }

    /**
     * {@inheritdoc}
     */
    public function userId()
    {
        if ($this->tokenContext->hasToken()) {
            return UserId::fromNative($this->tokenContext->getToken()->userId());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function userEmail()
    {
        if ($this->tokenContext->hasToken()) {
            return EmailAddress::fromNative($this->tokenContext->getToken()->email());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthenticated()
    {
        return $this->tokenContext->hasToken();
    }
}
