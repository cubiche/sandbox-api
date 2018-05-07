<?php


/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service\User\Mutation;

use Behat\Service\WebApiService;

/**
 * DisableUserService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class DisableUserService extends WebApiService
{
    /**
     * @param string $id
     */
    public function specifyId($id)
    {
        $this->set('id', $id);
    }

    /**
     * Disable user.
     */
    public function disable()
    {
        $this->send();
    }

    /**
     * @return bool
     */
    public function successful()
    {
        if ($this->hasErrors()) {
            return false;
        }

        return !$this->getResponse('disableUser')['enabled'];
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            mutation disableUser (
                $id: ID!
            ) { 
                disableUser(
                    id: $id
                ) {
                    enabled
                }
            }
        ';
    }
}
