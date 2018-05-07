<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Tests\Units\Language;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\System\Domain\Language\Command\CreateLanguageCommand;
use Sandbox\System\Domain\Language\Command\DisableLanguageCommand;
use Sandbox\System\Domain\Language\Command\EnableLanguageCommand;
use Sandbox\System\Domain\Language\Command\UpdateLanguageNameCommand;
use Sandbox\System\Domain\Language\Language;
use Sandbox\System\Domain\Language\LanguageId;
use Sandbox\System\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Locale\LocaleCode;

/**
 * LanguageCommandHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageCommandHandlerTests extends TestCase
{
    private function addLanguage()
    {
        $languageId = LanguageId::next();

        $this->commandBus()->dispatch(new CreateLanguageCommand(
            $languageId->toNative(),
            LanguageCode::EN,
            array('en_US' => 'English', 'es_ES' => 'Ingles')
        ));

        return $languageId;
    }

    private function repository()
    {
        return $this->writeRepository(Language::class);
    }

    /**
     * Test createLanguage method.
     */
    public function testCreateLanguage()
    {
        /* @var Language $language */
        $this
            ->given($languageId = LanguageId::next())
            ->and(
                $command = new CreateLanguageCommand(
                    $languageId->toNative(),
                    LanguageCode::EN,
                    array('en_US' => 'English', 'es_ES' => 'Ingles')
                )
            )
            ->then()
                ->variable($this->repository()->get($languageId))
                    ->isNull()
            ->and()
            ->when($this->commandBus()->dispatch($command))
            ->then()
                ->object($language = $this->repository()->get($languageId))
                    ->isInstanceOf(Language::class)
                ->string($language->name()->translate(LocaleCode::ES_ES()))
                    ->isEqualTo('Ingles')
        ;
    }

    /**
     * Test enable/disable method.
     */
    public function testEnableDisableLanguage()
    {
        /* @var Language $language */
        $this
            ->given($languageId = $this->addLanguage())
            ->then()
                ->object($language = $this->repository()->get($languageId))
                    ->isInstanceOf(Language::class)
                ->boolean($language->isEnabled())
                    ->isTrue()
            ->and()
            ->when(
                $this->commandBus()->dispatch(new DisableLanguageCommand(
                    $languageId->toNative()
                ))
            )
            ->and($language = $this->repository()->get($languageId))
            ->then()
                ->boolean($language->isEnabled())
                    ->isFalse()
                ->exception(function () {
                    $this->commandBus()->dispatch(new DisableLanguageCommand(
                        LanguageId::next()->toNative()
                    ));
                })->isInstanceOf(NotFoundException::class)
            ->and()
            ->when(
                $this->commandBus()->dispatch(new EnableLanguageCommand(
                    $languageId->toNative()
                ))
            )
            ->and($language = $this->repository()->get($languageId))
            ->then()
                ->boolean($language->isEnabled())
                    ->isTrue()
        ;
    }

    /**
     * Test UpdateLanguageName method.
     */
    public function testUpdateLanguageName()
    {
        /* @var Language $language */
        $this
            ->given($languageId = $this->addLanguage())
            ->and($language = $this->repository()->get($languageId))
            ->and($name = array('en_US' => 'Spanish', 'es_ES' => 'Español'))
            ->then()
                ->array($language->name()->toArray())
                    ->isNotEqualTo($name)
            ->when(
                $this->commandBus()->dispatch(
                    new UpdateLanguageNameCommand(
                        $languageId->toNative(),
                        $name
                    )
                )
            )
            ->and($language = $this->repository()->get($languageId))
            ->then()
                ->array($language->name()->toArray())
                    ->isEqualTo($name)
        ;
    }
}
