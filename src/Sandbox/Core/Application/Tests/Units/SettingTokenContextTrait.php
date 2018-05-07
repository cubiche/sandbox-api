<?php

/**
 * This file is part of the Sandbox component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Application\Tests\Units;

use Sandbox\Core\Application\Service\TokenContextInterface;
use Sandbox\Core\Application\Service\TokenManager;
use Sandbox\Core\Application\Tests\Fixtures\TokenContext;

/**
 * SettingTokenContext trait..
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait SettingTokenContextTrait
{
    /**
     * @var TokenContextInterface
     */
    protected $tokenContext;

    /**
     * @return TokenManager
     */
    protected function getTokenManager()
    {
        return new TokenManager(
            'api.example.com',
            'example.com',
            'app/Resources/cert/jwt-rsa-public.pem',
            'app/Resources/cert/jwt-rsa-private.key'
        );
    }
    /**
     * @return TokenContextInterface
     */
    protected function getTokenContext()
    {
        if ($this->tokenContext === null) {
            $tokenManager = $this->getTokenManager();
            $this->tokenContext = new TokenContext($tokenManager);

            $jwt = $tokenManager->encode(
                'f3623738-c38e-4189-8f2c-8896f4e524ec',
                'ivan@cubiche.com',
                array('app.order', 'app.sales')
            );

            $this->tokenContext->setJWT($jwt);
        }

        return $this->tokenContext;
    }

    /**
     * @return TokenContextInterface
     */
    protected function getEmptyTokenContext()
    {
        $tokenManager = $this->getTokenManager();

        return new TokenContext($tokenManager);
    }
}
