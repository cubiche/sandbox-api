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
 * PasswordEncoder class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class PasswordEncoder implements PasswordEncoderInterface
{
    const MAX_PASSWORD_LENGTH = 4096;

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
     * @var int
     */
    protected $length;

    /**
     * PasswordEncoder constructor.
     *
     * @param string $algorithm
     * @param bool   $encodeHashAsBase64
     * @param int    $iterations
     * @param int    $length
     */
    public function __construct($algorithm = 'sha512', $encodeHashAsBase64 = true, $iterations = 1000, $length = 40)
    {
        $this->algorithm = $algorithm;
        $this->encodeHashAsBase64 = $encodeHashAsBase64;
        $this->iterations = $iterations;
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($plainPassword, $salt)
    {
        if ($this->isPasswordTooLong($plainPassword)) {
            throw new \InvalidArgumentException('Too long password.');
        }

        if (!in_array($this->algorithm, hash_algos(), true)) {
            throw new \LogicException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
        }

        $digest = hash_pbkdf2($this->algorithm, $plainPassword, $salt, $this->iterations, $this->length, true);

        return $this->encodeHashAsBase64 ? base64_encode($digest) : bin2hex($digest);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    protected function isPasswordTooLong($password)
    {
        return strlen($password) > self::MAX_PASSWORD_LENGTH;
    }
}
