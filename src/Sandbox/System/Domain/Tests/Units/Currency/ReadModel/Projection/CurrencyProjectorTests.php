<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Currency\ReadModel\Projection;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\System\Domain\Currency\Command\CreateCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\DisableCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\EnableCurrencyCommand;
use Sandbox\System\Domain\Currency\Command\UpdateCurrencyNameCommand;
use Sandbox\System\Domain\Currency\CurrencyCode;
use Sandbox\System\Domain\Currency\Event\CurrencyWasEnabled;
use Sandbox\System\Domain\Currency\CurrencyId;
use Sandbox\System\Domain\Currency\ReadModel\Currency as ReadModelCurrency;
use Sandbox\System\Domain\Currency\ReadModel\Projection\CurrencyProjector;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\EventSourcing\ReadModelInterface;

/**
 * CurrencyProjectorTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CurrencyProjectorTests extends TestCase
{
    /**
     * Test create method.
     */
    public function testCreate()
    {
        $this
            ->given(
                $projector = new CurrencyProjector(
                    $this->queryRepository(ReadModelCurrency::class)
                )
            )
            ->then()
                ->array($projector->getSubscribedEvents())
                    ->isNotEmpty()
        ;
    }

    private function repository()
    {
        return $this->queryRepository(ReadModelCurrency::class);
    }

    private function addCurrency()
    {
        $currencyId = CurrencyId::next();

        $this->commandBus()->dispatch(new CreateCurrencyCommand(
            $currencyId->toNative(),
            CurrencyCode::EUR,
            '€',
            array('en_US' => 'Euro', 'es_ES' => 'Euro')
        ));

        return $currencyId;
    }

    /**
     * Test WhenCurrencyWasCreated method.
     */
    public function testWhenCurrencyWasCreated()
    {
        $this
            ->then()
                ->boolean($this->repository()->isEmpty())
                    ->isTrue()
                ->and()
                ->when($currencyId = $this->addCurrency())
                ->then()
                    ->boolean($this->repository()->isEmpty())
                        ->isFalse()
                    ->object($this->repository()->get($currencyId))
                        ->isInstanceOf(ReadModelInterface::class)
        ;
    }

    /**
     * Test when currency was Enabled/Disabled method.
     */
    public function testWhenCurrencyWasEnabledDisabled()
    {
        /* @var ReadModelCurrency $currency */
        $this
            ->given($currencyId = $this->addCurrency())
            ->then()
                ->object($currency = $this->repository()->get($currencyId))
                    ->isInstanceOf(ReadModelCurrency::class)
                ->boolean($currency->isEnabled())
                    ->isTrue()
            ->and()
            ->when($this->commandBus()->dispatch(new DisableCurrencyCommand($currencyId->toNative())))
            ->then()
                ->object($currency = $this->repository()->get($currencyId))
                    ->isInstanceOf(ReadModelCurrency::class)
                ->boolean($currency->isEnabled())
                    ->isFalse()
                ->and()
                ->when($this->commandBus()->dispatch(new EnableCurrencyCommand($currencyId->toNative())))
                ->then()
                    ->object($currency = $this->repository()->get($currencyId))
                        ->isInstanceOf(ReadModelCurrency::class)
                    ->boolean($currency->isEnabled())
                        ->isTrue()
        ;
    }

    /**
     * Test WhenCurrencyNameWasUpdated method.
     */
    public function testWhenCurrencyNameWasUpdated()
    {
        /* @var ReadModelCurrency $currency */
        $this
            ->given($currencyId = $this->addCurrency())
            ->and($currency = $this->repository()->get($currencyId))
            ->and($name = array('en_US' => 'Pound', 'es_ES' => 'Libra'))
            ->then()
                ->array($currency->name()->toArray())
                    ->isNotEqualTo($name)
            ->when(
                $this->commandBus()->dispatch(
                    new UpdateCurrencyNameCommand(
                        $currencyId->toNative(),
                        $name
                    )
                )
            )
            ->and($currency = $this->repository()->get($currencyId))
            ->then()
                ->array($currency->name()->toArray())
                    ->isEqualTo($name)
        ;
    }

    public function testNoExistingCurrency()
    {
        $this
            ->given($currencyId = CurrencyId::next())
            ->then()
            ->exception(function () use ($currencyId) {
                DomainEventPublisher::publish(new CurrencyWasEnabled($currencyId));
            })->isInstanceOf(NotFoundException::class)
        ;
    }
}
