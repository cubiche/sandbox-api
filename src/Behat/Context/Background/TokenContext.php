<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Background;

use Behat\Behat\Context\Context;
use Behat\Service\SharedStorageInterface;
use Sandbox\Core\Application\Service\TokenContextInterface;
use Sandbox\Core\Application\Service\TokenEncoderInterface;
use Cubiche\Domain\Identity\UUID;

/**
 * TokenContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class TokenContext implements Context
{
    /**
     * @var TokenContextInterface
     */
    protected $tokenContext;

    /**
     * @var TokenEncoderInterface
     */
    protected $tokenEncoder;

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * TokenContext constructor.
     *
     * @param TokenContextInterface  $tokenContext
     * @param TokenEncoderInterface  $tokenEncoder
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        TokenContextInterface $tokenContext,
        TokenEncoderInterface $tokenEncoder,
        SharedStorageInterface $sharedStorage
    ) {
        $this->tokenContext = $tokenContext;
        $this->tokenEncoder = $tokenEncoder;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * Set the token in the context.
     */
    public function setTokenInContext()
    {
        $jwt = $this->tokenEncoder->encode(
            UUID::nextUUIDValue(),
            'ivan@cubiche.com',
            array()
        );

        $this->tokenContext->setJWT($jwt);
    }

    /**
     * Clear the token in the context.
     */
    public function clearTokenInContext()
    {
        $this->tokenContext->setJWT(null);
    }
}
