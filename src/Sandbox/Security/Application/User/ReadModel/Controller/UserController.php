<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\User\ReadModel\Controller;

use Cubiche\Core\Collections\CollectionInterface;
use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\User\ReadModel\Query\FindAllUsers;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserById;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByPasswordResetToken;
use Sandbox\Security\Domain\User\ReadModel\User;

/**
 * UserController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserController extends QueryController
{
    /**
     * @return CollectionInterface|User[]
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(new FindAllUsers());
    }

    /**
     * @param string $passwordResetToken
     *
     * @return User
     */
    public function findOneByPasswordResetTokenAction($passwordResetToken)
    {
        /** @var User $user */
        $user = $this->queryBus()->dispatch(new FindOneUserByPasswordResetToken($passwordResetToken));
        if ($user === null || !$user->isEnabled()) {
            throw new NotFoundException(sprintf(
                'There is no user with passwordResetToken: %s',
                $passwordResetToken
            ));
        }

        return $user;
    }

    /**
     * @param $userId
     *
     * @return User
     */
    public function findOneByIdAction($userId)
    {
        /** @var User $user */
        $user = $this->queryBus()->dispatch(new FindOneUserById($userId));
        if ($user === null) {
            throw new NotFoundException(sprintf(
                'There is no user with id: %s',
                $userId
            ));
        }

        return $user;
    }
}
