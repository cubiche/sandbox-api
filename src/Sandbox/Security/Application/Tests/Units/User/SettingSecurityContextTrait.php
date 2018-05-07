<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\Tests\Units\User;

use Sandbox\Core\Application\Tests\Units\SettingTokenContextTrait;
use Sandbox\Security\Application\User\Service\SecurityContext;
use Sandbox\Security\Domain\User\Service\SecurityContextInterface;

/**
 * SettingSecurityContextTrait class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait SettingSecurityContextTrait
{
    use SettingTokenContextTrait;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext = null;

    /**
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        if ($this->securityContext === null) {
            $this->securityContext = new SecurityContext($this->getTokenContext());
        }

        return $this->securityContext;
    }

    /**
     * @return SecurityContextInterface
     */
    protected function getEmptySecurityContext()
    {
        return new SecurityContext($this->getEmptyTokenContext());
    }
}
