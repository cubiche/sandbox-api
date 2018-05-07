<?php

/** * This file is part of the Sandbox application.
 * Copyright (c) Cubiche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source conferenceId.
 */

namespace Sandbox\Conference\Domain\ReadModel\Query;

use Cubiche\Core\Cqrs\Query\Query;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * FindOneConferenceById class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindOneConferenceById extends Query
{
    /**
     * @var string
     */
    protected $conferenceId;

    /**
     * FindOneConferenceById constructor.
     *
     * @param string $conferenceId
     */
    public function __construct($conferenceId)
    {
        $this->conferenceId = $conferenceId;
    }

    /**
     * @return string
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('conferenceId', Assertion::uuid()->notBlank());
    }
}
