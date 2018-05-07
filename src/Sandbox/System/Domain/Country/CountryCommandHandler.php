<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Country;

use Sandbox\System\Domain\Country\Command\CreateCountryCommand;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * CountryCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CountryCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * CurrencyCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateCountryCommand $command
     */
    public function createCountry(CreateCountryCommand $command)
    {
        $country = new Country(
            CountryId::fromNative($command->countryId()),
            StringLiteral::fromNative($command->code()),
            LocalizableString::fromArray($command->name(), $command->defaultLocale())
        );

        $this->repository->persist($country);
    }
}
