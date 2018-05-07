<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Application\Controller;

use Sandbox\Core\Application\Service\TokenContextInterface;
use Sandbox\Core\Domain\Exception\AccessDeniedException;

/**
 * UserAware trait.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait UserAwareTrait
{
    /**
     * @var TokenContextInterface
     */
    protected $tokenContext;

    /**
     * @param TokenContextInterface $tokenContext
     */
    public function setTokenContext(TokenContextInterface $tokenContext)
    {
        $this->tokenContext = $tokenContext;
    }

    /**
     * @return string
     */
    protected function findCurrentUserOr401()
    {
        if ($this->tokenContext->hasToken()) {
            $token = $this->tokenContext->getToken();

            return $token->userId();
        }

        throw new AccessDeniedException('Protected resource');
    }
}
