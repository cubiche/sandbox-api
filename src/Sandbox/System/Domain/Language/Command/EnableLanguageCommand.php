<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * EnableLanguageCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EnableLanguageCommand extends Command
{
    /**
     * @var string
     */
    protected $languageId;

    /**
     * EnableLanguageCommand constructor.
     *
     * @param string $languageId
     */
    public function __construct($languageId)
    {
        $this->languageId = $languageId;
    }

    /**
     * @return string
     */
    public function languageId()
    {
        return $this->languageId;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('languageId', Assertion::uuid()->notBlank());
    }
}
