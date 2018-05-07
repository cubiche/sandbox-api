<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\Tests\Units\User;

use Cubiche\Domain\Identity\UUID;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\User;
use Sandbox\Security\Domain\User\UserId;

/**
 * UserTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserTests extends TestCase
{
    /**
     * @return User
     */
    protected function createUser()
    {
        return new User(
            UserId::next(),
            StringLiteral::fromNative('johnsnow'),
            StringLiteral::fromNative('johnsnow'),
            StringLiteral::fromNative('salt'),
            StringLiteral::fromNative('johnsnow'),
            EmailAddress::fromNative('johnsnow@gameofthrones.com'),
            EmailAddress::fromNative('johnsnow@gameofthrones.com')
        );
    }

    /**
     * Test create User.
     */
    public function testCreatingUser()
    {
        $this
            ->given($usernameCanonical = 'johnsnow')
            ->and($emailCanonical = 'johnsnow@gameofthrones.com')
            ->and($salt = 'salt')
            ->and(
                $user = new User(
                    UserId::next(),
                    StringLiteral::fromNative('johnsnow'),
                    StringLiteral::fromNative($usernameCanonical),
                    StringLiteral::fromNative($salt),
                    StringLiteral::fromNative('johnsnow'),
                    EmailAddress::fromNative('johnsnow@gameofthrones.com'),
                    EmailAddress::fromNative($emailCanonical),
                    [
                        UUID::next(),
                    ]
                )
            )
            ->then()
                ->object($user)
                    ->isInstanceOf(User::class)
                ->string($user->salt()->toNative())
                    ->isEqualTo($salt)
                ->string($user->usernameCanonical()->toNative())
                    ->isEqualTo($usernameCanonical)
                ->string($user->emailCanonical()->toNative())
                    ->isEqualTo($emailCanonical)
        ;
    }

    /**
     * Test ResetPasswordRequest method.
     */
    public function testResetPasswordRequest()
    {
        $this
            ->given($passwordRequestToken = '123abc')
            ->and($user = $this->createUser())
            ->then($user->resetPasswordRequest(StringLiteral::fromNative($passwordRequestToken)))
                ->string($user->passwordResetToken()->toNative())
                    ->isEqualTo($passwordRequestToken)
                ->variable($user->passwordRequestedAt())
                    ->isNotNull()
        ;
    }

    /**
     * Test ResetPassword method.
     */
    public function testResetPassword()
    {
        $this
            ->given($password = '123abc')
            ->and($user = $this->createUser())
            ->then($user->resetPassword(StringLiteral::fromNative($password)))
                ->string($user->password()->toNative())
                    ->isEqualTo($password)
        ;
    }

    /**
     * Test Login method.
     */
    public function testLogin()
    {
        $this
            ->given($user = $this->createUser())
            ->then()
                ->variable($user->lastLogin())
                    ->isNull()
            ->then($user->login())
                ->variable($user->lastLogin())
                    ->isNotNull()

        ;
    }

    /**
     * Test Logout method.
     */
    public function testLogout()
    {
        $this
            ->given($user = $this->createUser())
            ->then()
                ->variable($user->lastLogout())
                    ->isNull()
            ->then($user->logout())
                ->variable($user->lastLogout())
                    ->isNotNull()

        ;
    }

    /**
     * Test VerificationRequest method.
     */
    public function testVerificationRequest()
    {
        $this
            ->given($user = $this->createUser())
            ->and($emailVerificationToken = '123abc')
            ->then()
                ->boolean($user->isVerified())
                    ->isTrue()
            ->and()
            ->then($user->verificationRequest(StringLiteral::fromNative($emailVerificationToken)))
                ->boolean($user->isVerified())
                    ->isFalse()
                ->string($user->emailVerificationToken()->toNative())
                    ->isEqualTo($emailVerificationToken)
        ;
    }

    /**
     * Test Verified method.
     */
    public function testVerified()
    {
        $this
            ->given($user = $this->createUser())
            ->and($user->verificationRequest(StringLiteral::fromNative('123abc')))
            ->then()
                ->boolean($user->isVerified())
                    ->isFalse()
            ->and()
            ->then($user->verified())
                ->boolean($user->isVerified())
                    ->isTrue()
        ;
    }

    /**
     * Test Enable method.
     */
    public function testEnable()
    {
        $this
            ->given($user = $this->createUser())
            ->and($user->disable())
            ->then()
                ->boolean($user->isEnabled())
                    ->isFalse()
            ->then($user->enable())
                ->boolean($user->isEnabled())
                    ->isTrue()
        ;
    }

    /**
     * Test Disable method.
     */
    public function testDisable()
    {
        $this
            ->given($user = $this->createUser())
            ->then()
                ->boolean($user->isEnabled())
                    ->isTrue()
            ->then($user->disable())
                ->boolean($user->isEnabled())
                    ->isFalse()
        ;
    }

    /**
     * Test AddRole method.
     */
    public function testAddRole()
    {
        $this
            ->given($user = $this->createUser())
            ->and($roleId = RoleId::fromNative($this->faker->uuid))
            ->then()
                ->boolean($user->roles()->contains($roleId))
                    ->isFalse()
            ->then($user->addRole($roleId))
                ->boolean($user->roles()->contains($roleId))
                    ->isTrue()
        ;
    }

    /**
     * Test RemoveRole method.
     */
    public function testRemoveRole()
    {
        $this
            ->given($user = $this->createUser())
            ->and($roleId = RoleId::fromNative($this->faker->uuid))
            ->and($user->addRole($roleId))
            ->then()
                ->boolean($user->roles()->contains($roleId))
                    ->isTrue()
            ->then($user->removeRole($roleId))
                ->boolean($user->roles()->contains($roleId))
                    ->isFalse()
        ;
    }
}
