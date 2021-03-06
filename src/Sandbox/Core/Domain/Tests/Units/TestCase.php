<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Domain\Tests\Units;

use Cubiche\Core\Validator\Validator;
use Cubiche\Domain\Geolocation\Validator\Asserter as GeolocationAsserter;
use Cubiche\Domain\Locale\Validator\Asserter as LocaleAsserter;
use Cubiche\Tests\TestCase as BaseTestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;
use Closure;

/**
 * TestCase class.
 *
 * Generated by TestGenerator on 2017-09-13 at 12:50:27.
 */
abstract class TestCase extends BaseTestCase
{
    use SettingQueryBusTrait;

    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param Closure   $reflectionClassFactory
     * @param Closure   $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        Closure $reflectionClassFactory = null,
        Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Equatable\Tests\Asserters');
        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Collections\Tests\Asserters');

        $this->getAssertionManager()->setAlias('variable', 'VariableAsserter');
        $this->getAssertionManager()->setAlias('collection', 'CollectionAsserter');
        $this->getAssertionManager()->setAlias('list', 'ListAsserter');
        $this->getAssertionManager()->setAlias('set', 'SetAsserter');
        $this->getAssertionManager()->setAlias('hashmap', 'HashMapAsserter');
        $this->getAssertionManager()->setAlias('datasource', 'DataSourceAsserter');
    }

    /**
     * Register custom validator asserters.
     */
    protected function registerValidatorAsserters()
    {
        $geolocationAsserter = new GeolocationAsserter($this->queryBus());
        $localeAsserter = new LocaleAsserter($this->queryBus());

        Validator::registerValidator('distanceUnit', array($geolocationAsserter, 'distanceUnit'));
        Validator::registerValidator('countryCode', array($localeAsserter, 'countryCode'));
        Validator::registerValidator('languageCode', array($localeAsserter, 'languageCode'));
        Validator::registerValidator('localeCode', array($localeAsserter, 'localeCode'));
    }
}
