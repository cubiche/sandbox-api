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
 * EnableUserService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EnableUserService extends WebApiService
{
    /**
     * @param string $id
     */
    public function specifyId($id)
    {
        $this->set('id', $id);
    }

    /**
     * Enable user.
     */
    public function enable()
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

        return $this->getResponse('enableUser')['enabled'];
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            mutation enableUser (
                $id: ID!
            ) { 
                enableUser(
                    id: $id
                ) {
                    enabled
                }
            }
        ';
    }
}
