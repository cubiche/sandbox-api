<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Application\User;

use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Sandbox\Core\Application\Service\TokenContextInterface;
use Sandbox\Core\Application\Service\TokenEncoderInterface;
use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\Security\Application\User\Command\GenerateJWTCommand;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Domain\User\UserId;

/**
 * SecurityCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SecurityCommandHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * @var TokenContextInterface
     */
    protected $tokenContext;

    /**
     * @var TokenEncoderInterface
     */
    protected $tokenEncoder;

    /**
     * SecurityCommandHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     * @param TokenContextInterface    $tokenContext
     * @param TokenEncoderInterface    $tokenEncoder
     */
    public function __construct(
        QueryRepositoryInterface $repository,
        TokenContextInterface $tokenContext,
        TokenEncoderInterface $tokenEncoder
    ) {
        $this->repository = $repository;
        $this->tokenContext = $tokenContext;
        $this->tokenEncoder = $tokenEncoder;
    }

    /**
     * @param GenerateJWTCommand $command
     */
    public function generateJWT(GenerateJWTCommand $command)
    {
        $user = $this->findUserOr404($command->userId());
        $jwt = $this->tokenEncoder->encode(
            $user->userId()->toNative(),
            $user->email()->toNative(),
            array_map(function (StringLiteral $permission) {
                return $permission->toNative();
            }, $user->permissions()->toArray())
        );

        $this->tokenContext->setJWT($jwt);
    }

    /**
     * @param string $userId
     *
     * @return User
     */
    private function findUserOr404($userId)
    {
        /** @var User $user */
        $user = $this->repository->get(UserId::fromNative($userId));
        if ($user === null) {
            throw new NotFoundException(sprintf(
                'There is no user with id: %s',
                $userId
            ));
        }

        return $user;
    }
}
