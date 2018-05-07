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
 * Canonicalizer class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Canonicalizer implements CanonicalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function canonicalize($string)
    {
        return null === $string ? null : mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string));
    }
}
