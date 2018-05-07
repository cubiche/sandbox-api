<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\ReadModel;

use Sandbox\Security\Domain\User\ReadModel\Query\FindAllUsers;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmail;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmailVerificationToken;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserById;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByPasswordResetToken;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByUsername;
use Sandbox\Security\Domain\User\Service\CanonicalizerInterface;
use Sandbox\Security\Domain\User\UserId;
use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * UserQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * @var CanonicalizerInterface
     */
    protected $canonicalizer;

    /**
     * UserQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $repository
     * @param CanonicalizerInterface   $canonicalizer
     */
    public function __construct(QueryRepositoryInterface $repository, CanonicalizerInterface $canonicalizer)
    {
        $this->repository = $repository;
        $this->canonicalizer = $canonicalizer;
    }

    /**
     * @param FindAllUsers $query
     *
     * @return CollectionInterface|User[]
     */
    public function findAllUsers(FindAllUsers $query)
    {
        return $this->repository->getIterator();
    }

    /**
     * @param FindOneUserByEmailVerificationToken $query
     *
     * @return User
     */
    public function findOneUserByEmailVerificationToken(FindOneUserByEmailVerificationToken $query)
    {
        return $this->repository->findOne(
            Criteria::property('emailVerificationToken')->eq(
                StringLiteral::fromNative($query->emailVerificationToken())
            )
        );
    }

    /**
     * @param FindOneUserByPasswordResetToken $query
     *
     * @return User
     */
    public function findOneUserByPasswordResetToken(FindOneUserByPasswordResetToken $query)
    {
        return $this->repository->findOne(
            Criteria::property('passwordResetToken')->eq(StringLiteral::fromNative($query->passwordResetToken()))
        );
    }

    /**
     * @param FindOneUserByUsername $query
     *
     * @return User
     */
    public function findOneUserByUsername(FindOneUserByUsername $query)
    {
        $username = $this->canonicalizer->canonicalize($query->username());

        return $this->repository->findOne(
            Criteria::property('username')->eq(StringLiteral::fromNative($username))
        );
    }

    /**
     * @param FindOneUserByEmail $query
     *
     * @return User
     */
    public function findOneUserByEmail(FindOneUserByEmail $query)
    {
        $email = $this->canonicalizer->canonicalize($query->email());

        return $this->repository->findOne(
            Criteria::property('email')->eq(EmailAddress::fromNative($email))
        );
    }

    /**
     * @param FindOneUserById $query
     *
     * @return User
     */
    public function findOneUserById(FindOneUserById $query)
    {
        return $this->repository->findOne(
            Criteria::property('id')->eq(UserId::fromNative($query->userId()))
        );
    }
}
