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
 * Canonicalizer interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface CanonicalizerInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function canonicalize($string);
}
