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
 * UpdateLanguageNameCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UpdateLanguageNameCommand extends Command
{
    /**
     * @var string
     */
    protected $languageId;

    /**
     * @var array
     */
    protected $name;

    /**
     * UpdateLanguageNameCommand constructor.
     *
     * @param string $languageId
     * @param array  $name
     */
    public function __construct($languageId, array $name)
    {
        $this->languageId = $languageId;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function languageId()
    {
        return $this->languageId;
    }

    /**
     * @return array
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('languageId', Assertion::uuid()->notBlank());

        $classMetadata->addPropertyConstraint(
            'name',
            Assertion::isArray()->each(
                Assertion::localeCode()->notBlank(),
                Assertion::string()->notBlank()
            )
        );
    }
}
