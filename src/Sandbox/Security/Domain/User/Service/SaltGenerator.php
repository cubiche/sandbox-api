<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Service;

/**
 * SaltGenerator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SaltGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * @var bool
     */
    protected $encodeHashAsBase64;

    /**
     * @var int
     */
    protected $iterations;

    /**
     * SaltGenerator constructor.
     *
     * @param string $algorithm
     * @param bool   $encodeHashAsBase64
     * @param int    $iterations
     */
    public function __construct($algorithm = 'sha1', $encodeHashAsBase64 = true, $iterations = 1000)
    {
        $this->algorithm = $algorithm;
        $this->encodeHashAsBase64 = $encodeHashAsBase64;
        $this->iterations = $iterations;
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        if (!in_array($this->algorithm, hash_algos(), true)) {
            throw new \LogicException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
        }

        $salted = uniqid(mt_rand(), true);
        $digest = hash($this->algorithm, $salted, true);

        // "stretch" hash
        for ($i = 1; $i < $this->iterations; ++$i) {
            $digest = hash($this->algorithm, $digest.$salted, true);
        }

        return $this->encodeHashAsBase64 ? base64_encode($digest) : bin2hex($digest);
    }
}
