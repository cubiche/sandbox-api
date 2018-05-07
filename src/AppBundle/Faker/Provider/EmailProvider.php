<?php

namespace AppBundle\Faker\Provider;

use Faker\Provider\Base;

/**
 * EmailProvider class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EmailProvider extends Base
{
    /**
     * @return string
     */
    public function emailVerificationToken()
    {
        return $this->generator->sha1();
    }

    /**
     * @return string
     */
    public function passwordResetToken()
    {
        return $this->generator->sha1();
    }
}
