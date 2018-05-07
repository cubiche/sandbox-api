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
 * TokenGenerator class.
 *
 * Solution taken from stackoverflow: http://stackoverflow.com/a/13733588/1056679
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TokenGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    protected $alphabet;

    /**
     * @var int
     */
    protected $alphabetLength;

    /**
     * @var int
     */
    protected $length;

    /**
     * TokenGenerator constructor.
     *
     * @param int $length
     */
    public function __construct($length = 56)
    {
        $this->alphabet = implode(range('a', 'z')).implode(range('A', 'Z')).implode(range(0, 9));
        $this->alphabetLength = strlen($this->alphabet);
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $token = '';
        for ($i = 0; $i < $this->length; ++$i) {
            $randomKey = $this->getKey(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }

        return $token;
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    private function getKey($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            return $min;
        }

        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);

        return $min + $rnd;
    }
}
