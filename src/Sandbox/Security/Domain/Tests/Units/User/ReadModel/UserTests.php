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

use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Sandbox\Security\Domain\Tests\Units\TestCase;
use Sandbox\Security\Domain\User\ReadModel\User;
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
            EmailAddress::fromNative('johnsnow@gameofthrones.com'),
            true,
            true
        );
    }

    /**
     * Test create a valid User.
     */
    public function testCreateUser()
    {
        $this
            ->given($userId = UserId::next())
            ->and($username = StringLiteral::fromNative('johnsnow'))
            ->and($email = EmailAddress::fromNative('johnsnow@gameofthrones.com'))
            ->when($user = new User($userId, $username, $email, true, true))
            ->then()
                ->string($user->userId()->toNative())
                    ->isEqualTo($userId->toNative())
                ->string($user->username()->toNative())
                    ->isEqualTo($username->toNative())
                ->string($user->email()->toNative())
                    ->isEqualTo($email->toNative())
        ;
    }

    /**
     * Test PasswordResetToken property.
     */
    public function testPasswordResetToken()
    {
        $this
            ->given($user = $this->createUser())
            ->and($passwordResetToken = 'a1b2c3')
            ->then()
                ->variable($user->passwordResetToken())
                    ->isNull()
            ->and()
            ->when($user->setPasswordResetToken(StringLiteral::fromNative($passwordResetToken)))
            ->then()
                ->string($user->passwordResetToken()->toNative())
                    ->isEqualTo($passwordResetToken)
        ;
    }

    /**
     * Test Enable property.
     */
    public function testEnable()
    {
        $this
            ->given($user = $this->createUser())
            ->then()
                ->boolean($user->isEnabled())
                    ->isTrue()
            ->and()
            ->when($user->disable())
            ->then()
                ->boolean($user->isEnabled())
                    ->isFalse()
            ->and()
            ->when($user->enable())
            ->then()
                ->boolean($user->isEnabled())
                    ->isTrue()
        ;
    }

    /**
     * Test IsVerified method.
     */
    public function testIsVerified()
    {
        $this
            ->given($user = $this->createUser())
            ->and($emailVerificationToken = 'a1b2c3')
            ->then()
                ->boolean($user->isVerified())
                    ->isTrue()
            ->and()
            ->when($user->needVerification(StringLiteral::fromNative($emailVerificationToken)))
            ->then()
                ->boolean($user->isVerified())
                    ->isFalse()
                ->string($user->emailVerificationToken()->toNative())
                    ->isEqualTo($emailVerificationToken)
            ->and()
            ->when($user->verified())
            ->then()
                ->boolean($user->isVerified())
                    ->isTrue()
        ;
    }

    /**
     * Test Permissions property.
     */
    public function testPermissions()
    {
        $this
            ->given($user = $this->createUser())
            ->then()
                ->integer($user->permissions()->count())
                    ->isEqualTo(0)
            ->and()
            ->when($user->addPermission(StringLiteral::fromNative('APP')))
            ->then()
                ->integer($user->permissions()->count())
                    ->isEqualTo(1)
            ->and()
            ->when($user->removePermission(StringLiteral::fromNative('APP')))
            ->then()
                ->integer($user->permissions()->count())
                    ->isEqualTo(0)
        ;
    }
}
