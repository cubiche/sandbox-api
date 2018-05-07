<?php


/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Tests\Units\Language\Controller;

use Sandbox\Core\Application\Tests\Units\SettingTokenContextTrait;
use Sandbox\System\Application\Language\Controller\LanguageController;
use Sandbox\System\Application\Tests\Units\TestCase;
use Sandbox\System\Domain\Language\LanguageId;
use Sandbox\System\Domain\Language\ReadModel\Language;
use Sandbox\System\Domain\Language\ReadModel\Query\FindOneLanguageByCode;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Locale\LocaleCode;

/**
 * LanguageControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageControllerTests extends TestCase
{
    use SettingTokenContextTrait;

    /**
     * @return LanguageController
     */
    protected function createController()
    {
        return new LanguageController($this->commandBus(), $this->getTokenContext());
    }

    /**
     * @param string $languageCode
     *
     * @return Language
     */
    protected function findOneLanguage($languageCode)
    {
        return $this->queryBus()->dispatch(new FindOneLanguageByCode($languageCode));
    }

    /**
     * Test CreateAction method.
     */
    public function testCreateAction()
    {
        $this
            ->given($controller = $this->createController())
            ->and($languageCode = LanguageCode::EN)
            ->and($name = array('en_US' => 'English', 'es_ES' => 'Ingles'))
            ->and($defaulLocale = LocaleCode::EN_US)
            ->when($language = $this->findOneLanguage($languageCode))
            ->then()
                ->variable($language)
                    ->isNull()
                ->and()
                ->when($languageId = $controller->createAction($languageCode, $name, $defaulLocale))
                ->then()
                    ->string($languageId)
                        ->isNotEmpty()
                    ->variable($language = $this->findOneLanguage($languageCode))
                        ->isNotNull()
                    ->string($language->name()->translate(LocaleCode::EN_US()))
                        ->isEqualTo('English')
        ;
    }

    /**
     * @return LanguageId
     */
    private function addLanguage()
    {
        $controller = $this->createController();
        $id = $controller->createAction(
            LanguageCode::EN,
            array('en_US' => 'English', 'es_ES' => 'Ingles'),
            'en_US'
        );

        return LanguageId::fromNative($id);
    }

    private function repository()
    {
        return $this->queryRepository(Language::class);
    }

    /**
     * Test disable/enable action method.
     */
    public function testEnableDisableAction()
    {
        /* @var Language $language */
        $this
            ->given($controller = $this->createController())
            ->and($languageId = $this->addLanguage())
            ->then()
                ->variable($language = $this->repository()->get($languageId))
                    ->isNotNull()
                ->string($language->name()->translate(LocaleCode::EN_US()))
                    ->isEqualTo('English')
                ->boolean($language->isEnabled())
                    ->isTrue()
                ->and()
                ->when($controller->disableAction($languageId))
                ->then()
                    ->variable($language = $this->repository()->get($languageId))
                        ->isNotNull()
                    ->boolean($language->isEnabled())
                        ->isFalse()
                    ->and()
                    ->when($controller->enableAction($languageId))
                    ->then()
                        ->variable($language = $this->repository()->get($languageId))
                            ->isNotNull()
                        ->boolean($language->isEnabled())
                            ->isTrue()
        ;
    }

    /**
     * Test update name action method.
     */
    public function testUpdateNameAction()
    {
        /* @var Language $language */
        $this
            ->given($controller = $this->createController())
            ->and($languageId = $this->addLanguage())
            ->and($language = $this->repository()->get($languageId))
            ->and($name = array('en_US' => 'English', 'es_ES' => 'Inglés'))
            ->then()
                ->array($language->name()->toArray())
                    ->isNotEqualTo($name)
            ->and()
            ->when($controller->updateNameAction($languageId->toNative(), $name))
            ->and($language = $this->repository()->get($languageId))
            ->then()
                ->array($language->name()->toArray())
                    ->isEqualTo($name)
        ;
    }
}
