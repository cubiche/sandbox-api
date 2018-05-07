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

use Cubiche\Core\Cqrs\Query\QueryBus;
use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmail;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Domain\User\Service\SecurityContextInterface;

/**
 * SecurityController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SecurityController extends QueryController
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * SecurityController constructor.
     *
     * @param QueryBus                 $queryBus
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(
        QueryBus $queryBus,
        SecurityContextInterface $securityContext
    ) {
        parent::__construct($queryBus);

        $this->securityContext = $securityContext;
    }

    /**
     * @return User
     */
    public function findAuthenticatedUserAction()
    {
        if ($this->securityContext->isAuthenticated()) {
            $email = $this->securityContext->userEmail()->toNative();

            return $this->queryBus()->dispatch(
                new FindOneUserByEmail($email)
            );
        }

        throw new NotFoundException('There is no authenticated user');
    }
}
