<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Security\Domain\User\Service;

use Sandbox\Security\Domain\Role\RoleId;
use Sandbox\Security\Domain\User\User;
use Sandbox\Security\Domain\User\UserId;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;

/**
 * UserFactory class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserFactory implements UserFactoryInterface
{
    /**
     * @var CanonicalizerInterface
     */
    protected $canonicalizer;

    /**
     * @var PasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @var GeneratorInterface
     */
    protected $saltGenerator;

    /**
     * UserServices constructor.
     *
     * @param CanonicalizerInterface   $canonicalizer
     * @param PasswordEncoderInterface $passwordEncoder
     * @param GeneratorInterface       $saltGenerator
     */
    public function __construct(
        CanonicalizerInterface $canonicalizer,
        PasswordEncoderInterface $passwordEncoder,
        GeneratorInterface $saltGenerator
    ) {
        $this->canonicalizer = $canonicalizer;
        $this->passwordEncoder = $passwordEncoder;
        $this->saltGenerator = $saltGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function create($userId, $username, $password, $email, array $roles = array())
    {
        $salt = $this->saltGenerator->generate();

        return new User(
            UserId::fromNative($userId),
            StringLiteral::fromNative($username),
            StringLiteral::fromNative($this->canonicalizer->canonicalize($username)),
            StringLiteral::fromNative($salt),
            StringLiteral::fromNative($this->passwordEncoder->encode($password, $salt)),
            EmailAddress::fromNative($email),
            EmailAddress::fromNative($this->canonicalizer->canonicalize($email)),
            array_map(function ($roleId) {
                return RoleId::fromNative($roleId);
            }, $roles)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function encodePassword($password, $salt)
    {
        return StringLiteral::fromNative($this->passwordEncoder->encode($password, $salt));
    }
}
