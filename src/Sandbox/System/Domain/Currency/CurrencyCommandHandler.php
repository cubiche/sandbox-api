<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Currency;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\System\Domain\Currency\Command\CreateCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\DisableCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\EnableCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\UpdateCurrencyNameCommand;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * CurrencyCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyCommandHandler
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
     * @param CreateCurrencyCommand $command
     */
    public function createCurrency(CreateCurrencyCommand $command)
    {
        $currency = new Currency(
            CurrencyId::fromNative($command->currencyId()),
            LocalizableString::fromArray($command->name(), $command->defaultLocale()),
            CurrencyCode::fromNative($command->code()),
            StringLiteral::fromNative($command->symbol())
        );

        $this->repository->persist($currency);
    }

    /**
     * @param UpdateCurrencyNameCommand $command
     */
    public function updateCurrencyName(UpdateCurrencyNameCommand $command)
    {
        $currency = $this->findOr404($command->currencyId());
        $currency->updateName(LocalizableString::fromArray($command->name()));

        $this->repository->persist($currency);
    }

    /**
     * @param EnableCurrencyCommand $command
     */
    public function enableCurrency(EnableCurrencyCommand $command)
    {
        $currency = $this->findOr404($command->currencyId());
        $currency->enable();

        $this->repository->persist($currency);
    }

    /**
     * @param DisableCurrencyCommand $command
     */
    public function disableCurrency(DisableCurrencyCommand $command)
    {
        $currency = $this->findOr404($command->currencyId());
        $currency->disable();

        $this->repository->persist($currency);
    }

    /**
     * @param string $currencyId
     *
     * @return Currency
     */
    private function findOr404($currencyId)
    {
        /** @var Currency $currency */
        $currency = $this->repository->get(CurrencyId::fromNative($currencyId));
        if ($currency === null) {
            throw new NotFoundException(sprintf(
                'There is no currency with id: %s',
                $currencyId
            ));
        }

        return $currency;
    }
}
