<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User\ReadModel;

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\ReadModel\Query\FindAllUsers;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmail;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByEmailVerificationToken;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserById;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByPasswordResetToken;
use Sandbox\Security\Domain\User\ReadModel\Query\FindOneUserByUsername;
use Sandbox\Security\Domain\User\ReadModel\User;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserQueryHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserQueryHandlerTests extends TestCase
{
    /**
     * @return QueryRepositoryInterface
     */
    protected function repository()
    {
        return $this->queryRepository(User::class);
    }

    /**
     * @return User
     */
    protected function addUserToRepository()
    {
        $user = new User(
            UserId::next(),
            StringLiteral::fromNative('johnsnow'),
            EmailAddress::fromNative('johnsnow@gameofthrones.com'),
            true,
            true
        );

        $this->repository()->persist($user);

        return $user;
    }

    /**
     * @return CollectionInterface|User[]
     */
    protected function dispatchFindAllUsers()
    {
        return $this->queryBus()->dispatch(new FindAllUsers());
    }

    /**
     * @param StringLiteral $emailVerificationToken
     *
     * @return User|null
     */
    protected function dispatchFindOneUserByEmailVerificationToken(StringLiteral $emailVerificationToken)
    {
        return $this->queryBus()->dispatch(
            new FindOneUserByEmailVerificationToken($emailVerificationToken->toNative())
        );
    }

    /**
     * @param StringLiteral $passwordResetToken
     *
     * @return User|null
     */
    protected function dispatchFindOneUserByPasswordResetToken(StringLiteral $passwordResetToken)
    {
        return $this->queryBus()->dispatch(new FindOneUserByPasswordResetToken($passwordResetToken->toNative()));
    }

    /**
     * @param StringLiteral $username
     *
     * @return User|null
     */
    protected function dispatchFindOneUserByUsername(StringLiteral $username)
    {
        return $this->queryBus()->dispatch(new FindOneUserByUsername($username->toNative()));
    }

    /**
     * @param EmailAddress $email
     *
     * @return User|null
     */
    protected function dispatchFindOneUserByEmail(EmailAddress $email)
    {
        return $this->queryBus()->dispatch(new FindOneUserByEmail($email->toNative()));
    }

    /**
     * @param UserId $userId
     *
     * @return User|null
     */
    protected function dispatchFindOneUserById(UserId $userId)
    {
        return $this->queryBus()->dispatch(new FindOneUserById($userId->toNative()));
    }

    /**
     * Test FindAllUsers method.
     */
    public function testFindAllUsers()
    {
        $this
            ->integer($this->dispatchFindAllUsers()->count())
                ->isEqualTo(0)
            ->and()
            ->when($this->addUserToRepository())
            ->then()
                ->integer($this->dispatchFindAllUsers()->count())
                    ->isEqualTo(1)
        ;
    }

    /**
     * Test FindOneUserByEmailVerificationToken method.
     */
    public function testFindOneUserByEmailVerificationToken()
    {
        $this
            ->given($emailVerificationToken = StringLiteral::fromNative('123abc'))
            ->and($user = $this->addUserToRepository())
            ->and($user->needVerification($emailVerificationToken))
            ->and($this->repository()->persist($user))
            ->when($userFound = $this->dispatchFindOneUserByEmailVerificationToken($emailVerificationToken))
            ->then()
                ->string($userFound->userId()->toNative())
                    ->isEqualTo($user->userId()->toNative())
            ->and()
            ->when(
                $userFound = $this->dispatchFindOneUserByEmailVerificationToken(
                    StringLiteral::fromNative('NOTEXIST')
                )
            )
            ->then()
                ->variable($userFound)
                    ->isNull()
        ;
    }

    /**
     * Test FindOneUserByPasswordResetToken method.
     */
    public function testFindOneUserByPasswordResetToken()
    {
        $this
            ->given($passwordResetToken = StringLiteral::fromNative('123abc'))
            ->and($user = $this->addUserToRepository())
            ->and($user->setPasswordResetToken($passwordResetToken))
            ->and($this->repository()->persist($user))
            ->when($userFound = $this->dispatchFindOneUserByPasswordResetToken($passwordResetToken))
            ->then()
                ->string($userFound->userId()->toNative())
                    ->isEqualTo($user->userId()->toNative())
            ->and()
            ->when(
                $userFound = $this->dispatchFindOneUserByPasswordResetToken(
                    StringLiteral::fromNative('NOTEXIST')
                )
            )
            ->then()
                ->variable($userFound)
                    ->isNull()
        ;
    }

    /**
     * Test FindOneUserByUsername method.
     */
    public function testFindOneUserByUsername()
    {
        $this
            ->given($user = $this->addUserToRepository())
            ->when($userFound = $this->dispatchFindOneUserByUsername($user->username()))
            ->then()
                ->string($userFound->userId()->toNative())
                    ->isEqualTo($user->userId()->toNative())
            ->and()
            ->when($userFound = $this->dispatchFindOneUserByUsername(StringLiteral::fromNative('NOTFOUND')))
            ->then()
                ->variable($userFound)
                    ->isNull()
        ;
    }

    /**
     * Test FindOneUserByEmail method.
     */
    public function testFindOneUserByEmail()
    {
        $this
            ->given($user = $this->addUserToRepository())
            ->when($userFound = $this->dispatchFindOneUserByEmail($user->email()))
            ->then()
                ->string($userFound->userId()->toNative())
                    ->isEqualTo($user->userId()->toNative())
            ->and()
            ->when($userFound = $this->dispatchFindOneUserByEmail(EmailAddress::fromNative('not@found.com')))
            ->then()
                ->variable($userFound)
                    ->isNull()
        ;
    }

    /**
     * Test FindOneUserById method.
     */
    public function testFindOneUserById()
    {
        $this
            ->given($user = $this->addUserToRepository())
            ->when($userFound = $this->dispatchFindOneUserById($user->id()))
            ->then()
                ->string($userFound->userId()->toNative())
                    ->isEqualTo($user->userId()->toNative())
            ->and()
            ->when($userFound = $this->dispatchFindOneUserById(UserId::next()))
            ->then()
                ->variable($userFound)
                    ->isNull()
        ;
    }
}
