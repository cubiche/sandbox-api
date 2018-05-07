# Steps to create a new aggregate root inside an existing bounded context
- For the example, i will use the `BusinessCycle` bounded context. In this bounded context, I will create the `Segment` aggregate root.
- `Segment` is an aggregate root which has an ID, is linked to an environment, is created inside a specific business cycle, a translatable name, a translatable description and a list of accounts liked to it.
- The example code below contains many annotations (like Sandbox package, author etc). Be sure you have customized your IDE to write them for you.

## 1. create a new folders in the domain layer
create a folder with path `src/Sandbox/BusinessCycle/Domain/Segment` along with a new folder in the test folder `src/Sandbox/BusinessCycle/Domain/Tests/Units/Segment`

## 2. create the Id class (SegmentId)
Create a new class in `src/Sandbox/BusinessCycle/Domain/Segment/SegmentId.php`
The aggregate id should extend the `Cubiche\Domain\Identity\UUID` class.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment;

use Cubiche\Domain\Identity\UUID;

/**
 * SegmentId class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentId extends UUID
{
}
```

In order to test this class, simply create a new file with path `src/Sandbox/BusinessCycle/Domain/Test/Units/Segment/SegmentIdTests.php` that extends the basic TestCase class already existing the the domain layer of your bounded context.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment;

use Sandbox\BusinessCycle\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * SegmentIdTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentIdTests extends TestCase
{
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(NativeValueObjectInterface::class)
        ;    
    }
}
```

## 3. create your events 
Those events should be placed in a new folder with path `src/Sandbox/BusinessCycle/Domain/Segment/Event`. They must extend the `Cubiche\Domain\EventSourcing\DomainEvent` class. [^1]
[^1]: For more information about DomainEvent, see page 123 of "DDD in PHP" book

In my context, I'm interested by the event related to the creation of a new segment alongs with the addition/removal of an account to/from a segment. For the example, i will only implement the case `SegmentWasCreated` event.

The event is like a bag that contains information of what happens. It is not possible to update later a event, so be careful and be sure you miss nothing in this class. [^2]
[^2]: "That is once you've created the event object this source data cannot be changed." https://martinfowler.com/eaaDev/DomainEvent.html

The data of the event are passed at construction time and are readable by those who are listening this event via the individual getter.

Notices :
- events are named in the past.
- my segment event contains 2 UUIDs (SegmentId, BusinessCycleId). I can type those variable with `Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId` and `Sandbox\BusinessCycle\Domain\Segment\SegmentId` because those ids are part of the same bounded context. If it was not the case, i would have typed the id with the class `Cubiche\Domain\Identity\UUID`

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\Event;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\Identity\UUID;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * SegmentWasCreated class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentWasCreated extends DomainEvent
{
    /**
     * @var UUID
     */
    protected $environmentId;

    /**
     * @var BusinessCycleId
     */
    protected $businessCycleId;

    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * @var LocalizableString
     */
    protected $description;

    /**
     * SegmentWasCreated constructor.
     *
     * @param SegmentId $segmentId
     * @param UUID $environmentId
     * @param BusinessCycleId $businessCycleId
     * @param LocalizableString $name
     * @param LocalizableString $description
     */
    public function __construct(
        SegmentId $segmentId,
        UUID $environmentId,
        BusinessCycleId $businessCycleId,
        LocalizableString $name,
        LocalizableString $description
    ) {
        parent::__construct($segmentId);

        $this->environmentId = $environmentId;
        $this->businessCycleId = $businessCycleId;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return SegmentId
     */
    public function segmentId()
    {
        return $this->aggregateId();
    }

    /**
     * @return SegmentId
     */
    public function id()
    {
        return $this->segmentId();
    }

    /**
     * @return UUID
     */
    public function environmentId()
    {
        return $this->environmentId;
    }

    /**
     * @return BusinessCycleId
     */
    public function businessCycleId()
    {
        return $this->businessCycleId;
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return LocalizableString
     */
    public function description()
    {
        return $this->description;
    }
}

```

The test class should be created at `src/Sandbox/BusinessCycle/Tests/Units/Segment/Event/SegmentWasCreatedTests.php`. The class should extend `Sandbox\Core\Domain\Tests\Units\Event\EventTestCase`. 

This test class should implement 2 methods (`getArguments` and `validatorProvider`). The first one should return an array with sample of arguments necessary in oder to construct an instance of the tested event. The second returns an array of validator test scenario. A scenario has an assertion result (`assert`) and the arguments necessary to make this assertion correct. The `EventTestCase` will test your event getters for you.


```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment\Event;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\Core\Domain\Tests\Units\Event\EventTestCase;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * SegmentWasCreatedTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentWasCreatedTests extends EventTestCase
{
    protected function getArguments()
    {
        return [
            SegmentId::next(),
            BusinessCycleId::next(),
            LocalizableString::fromArray(['fr_FR' => 'A SEGMENT NAME']),
            LocalizableString::fromArray(['fr_FR' => 'A SEGMENT DESCRIPTION']),
        ];
    }

    protected function validatorProvider()
    {
        return [
            [
                'assert' => true,
                'arguments' => $this->getArguments(),
            ],
        ];
    }
}
```

## 4. create the aggregate root
Create the aggregate root class `Sandbox\BusinessCycle\Domain\Segment\Segment`. This class should extends `Cubiche\Domain\EventSourcing\AggregateRoot`. The constructor of the aggregate root will receive arguments but doesn't set it directly to its properties. In order to grab those arguments inside the aggregate root, the constructor will record the event previously created (`SegmentWasCreated`). The aggegate root will *apply* that event in a method named `applySegmentWasCreated` by finally setting the event data to its properties.

So all actions that trigger a state change are implemented via domain events. For each domain event published there is an apply method responsible to reflect the state change. This pattern is called the `write model projection` [^3]
[^3]: see page 20 and following of the book "DDD in PHP"

in code, that give the folowing:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\Event\SegmentWasCreated;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * Segment class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Segment extends AggregateRoot
{
    /**
     * @var BusinessCycleId
     */
    protected $businessCycleId;

    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * @var LocalizableString
     */
    protected $description;

    /**
     * Segment constructor.
     *
     * @param SegmentId $segmentId
     * @param BusinessCycleId $businessCycleId
     * @param LocalizableString $name
     * @param LocalizableString $description
     */
    public function __construct(
        SegmentId $segmentId,
        BusinessCycleId $businessCycleId,
        LocalizableString $name,
        LocalizableString $description
    ) {
        parent::__construct($segmentId);

        $this->recordAndApplyEvent(
            new SegmentWasCreated($segmentId, $businessCycleId, $name, $description)
        );
    }

    /**
     * @return SegmentId
     */
    public function segmentId()
    {
        return $this->id;
    }

    /**
     * @return BusinessCycleId
     */
    public function businessCycleId()
    {
        return $this->businessCycleId;
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return LocalizableString
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @param SegmentWasCreated $event
     */
    public function applySegmentWasCreated(SegmentWasCreated $event)
    {
        $this->businessCycleId = $event->businessCycleId();
        $this->name = $event->name();
        $this->description = $event->description();
    }
}
```

The tests are placed in `Sandbox\BusinessCycle\Domain\Tests\Units\Segment\SegmentTests`. That class extends the basic TestCase class with the following content:
```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\Segment;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\BusinessCycle\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * SegmentTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentTests extends TestCase
{
    protected function createSegment($segmentId, $businessCycleId, $names, $descriptions)
    {
        return new Segment(
            $segmentId,
            $businessCycleId,
            LocalizableString::fromArray($names),
            LocalizableString::fromArray($descriptions)
        );
    }

    public function testPropertiesShouldBeFillingProperly()
    {
        $this
            ->given($segmentId = SegmentId::next())
            ->and($businessCycleId = BusinessCycleId::next())
            ->and($names = ['fr_FR' => 'UN NOM DE SEGMENT', 'en_US' => 'A SEGMENT NAME'])
            ->and($descriptions = ['fr_FR' => 'UNE DESCRIPTION DU SEGMENT', 'en_US' => 'A DESCRIPTION OF THE SEGMENT'])
            ->when($segment = $this->createSegment($segmentId, $businessCycleId, $names, $descriptions))
            ->then()
                ->string($segment->segmentId()->toNative())
                    ->isEqualTo($segmentId->toNative())
                ->string($segment->businessCycleId()->toNative())
                    ->isEqualTo($businessCycleId->toNative())
                ->string($segment->name()->toNative())
                    ->isEqualTo($names['en_US'])
                ->array($segment->name()->translations())
                    ->hasSize(2)
                    ->containsValues(['UN NOM DE SEGMENT', 'A SEGMENT NAME'])
                    ->hasKeys(['fr_FR', 'en_US'])
                ->string($segment->description()->toNative())
                    ->isEqualTo($descriptions['en_US'])
                ->array($segment->description()->translations())
                    ->hasSize(2)
                    ->containsValues(['UNE DESCRIPTION DU SEGMENT', 'A DESCRIPTION OF THE SEGMENT'])
                    ->hasKeys(['fr_FR', 'en_US'])
        ;
    }
}
```

## 5. Create the command
Create a command with path `src/Sandbox/BusinessCycle/Domain/Segment/Command/CreateSegmentCommand.php`. This class extends the `Cubiche\Core\Cqrs\Command\Command`.

The command is a design pattern. It is an extension of `Message`. You can summary it as a request for changing something inside the domain. The command will be sent to a command bus.

The command can contain some validations. This is done thanks to the static method `loadValidatorMetaData`.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Validator\Assert;

/**
 * CreateSegmentCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateSegmentCommand extends Command
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $businessCycleId;

    /**
     * @var array
     */
    protected $translatedNames;

    /**
     * @var array
     */
    protected $translatedDescriptions;

    /**
     * CreateSegmentCommand constructor.
     *
     * @param string $id
     * @param string $businessCycleId
     * @param array $translatedNames
     * @param array $translatedDescription
     */
    public function __construct($id, $businessCycleId, array $translatedNames, array $translatedDescriptions)
    {
        $this->id = $id;
        $this->businessCycleId = $businessCycleId;
        $this->translatedNames = $translatedNames;
        $this->translatedDescriptions = $translatedDescriptions;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function businessCycleId()
    {
        return $this->businessCycleId;
    }

    /**
     * @return array
     */
    public function translatedNames()
    {
        return $this->translatedNames;
    }

    /**
     * @return array
     */
    public function translatedDescriptions()
    {
        return $this->translatedDescriptions;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetaData(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('id', Assert::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('businessCycleId', Assert::uuid()->notBlank());
        $classMetadata->addPropertyConstraint(
            'translatedNames',
            Assert::arrayVal()->each(Assert::stringType()->notBlank(), Assert::localeCode()->notBlank())
        );
        $classMetadata->addPropertyConstraint(
            'translatedDescriptions',
            Assert::arrayVal()->each(Assert::stringType()->notBlank(), Assert::localeCode()->notBlank())
        );
    }
}
```

Concerning the test class, it is located at `Sandbox\BusinessCycle\Domain\Tests\Units\Segment\Command\CreateSegmentCommandTests`. That class extends the `Sandbox\Core\Domain\Tests\Units\Command\CommandTestCase`. The tests are construct the same way we did for the events, via 2 methods to be implemented (`getArguments` and `validatorProvider`). The `CommandTestCase` will test the getter of your Command for you:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment\Command;

use Sandbox\Core\Domain\Tests\Units\Command\CommandTestCase;

/**
 * CreateSegmentCommandTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateSegmentCommandTests extends CommandTestCase
{
    protected function getArguments()
    {
        return [
            $this->faker->uuid(),
            $this->faker->uuid(),
            [
                'fr_FR' => $this->faker->sentence(5),
                'en_US' => $this->faker->sentence(5),
            ],
            [
                'fr_FR' => $this->faker->sentence(5),
                'en_US' => $this->faker->sentence(5),
            ],
        ];
    }

    protected function validatorProvider()
    {
        return [
            [
                'assert' => true,
                'arguments' => $this->getArguments(),
            ],
            [
                'assert' => false,
                'arguments' => [
                    $this->faker->uuid(),
                    $this->faker->uuid(),
                    [
                        'francais' => $this->faker->sentence(5),
                        'anglais' => $this->faker->sentence(5),
                    ],
                    [
                        'fr_FR' => $this->faker->sentence(5),
                        'en_US' => $this->faker->sentence(5),
                    ],
                ],
            ],
            [
                'assert' => false,
                'arguments' => [
                    $this->faker->uuid(),
                    $this->faker->uuid(),
                    [
                        'en_EN' => '',
                        'fr_FR' => '',
                    ],
                    [
                        'fr_FR' => $this->faker->sentence(5),
                        'en_US' => $this->faker->sentence(5),
                    ],
                ],
            ],
        ];
    }
} 
```

## 6. Create the factory

For the factory, we need an interface + the factory implementation. Both must be placed in `Sandbox\BusinessCycle\Domain\Segment\Service`. Factory should do nothing else that creating a instance of the aggregate root.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\Service;

use Sandbox\BusinessCycle\Domain\Segment\Segment;

/**
 * SegmentFactoryInterface class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface SegmentFactoryInterface
{
    /**
     * @param string $segmentId
     * @param string $businessCycleId
     * @param array $translatedNames
     * @param array $translatedDescriptions
     *
     * @return Segment
     */
    public function create($segmentId, $businessCycleId, $translatedNames, $translatedDescriptions);
}
```

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\Service;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\Segment;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * SegmentFactory class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentFactory implements SegmentFactoryInterface
{
    /**
     * @param string $segmentId
     * @param string $businessCycleId
     * @param array $translatedNames
     * @param array $translatedDescriptions
     *
     * @return Segment
     */
    public function create($segmentId, $businessCycleId, $translatedNames, $translatedDescriptions)
    {
        return new Segment(
            SegmentId::fromNative($segmentId),
            BusinessCycleId::fromNative($businessCycleId),
            LocalizableString::fromArray($translatedNames),
            LocalizableString::fromArray($translatedDescriptions)
        );
    }
}
```

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment\Service;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\Segment;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\BusinessCycle\Domain\Segment\Service\SegmentFactory;
use Sandbox\BusinessCycle\Domain\Tests\Units\TestCase;

/**
 * SegmentFactoryTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentFactoryTests extends TestCase
{
    public function createFactory()
    {
        return new SegmentFactory();
    }

    public function testCreate()
    {
        $this
            ->given($factory = $this->createFactory())
            ->and($segmentId = SegmentId::nextUUIDValue())
            ->and($businessCycleId = BusinessCycleId::nextUUIDValue())
            ->and($names = ['fr_FR' => 'UN NOM DE SEGMENT', 'en_US' => 'A SEGMENT NAME'])
            ->and($descriptions = ['fr_FR' => 'UNE DESCRIPTION DU SEGMENT', 'en_US' => 'A DESCRIPTION OF THE SEGMENT'])
            ->when($segment = $factory->create($segmentId, $businessCycleId, $names, $descriptions))
                ->object($segment)
                    ->isInstanceOf(Segment::class)
                ->string($segment->segmentId()->toNative())
                    ->isEqualTo($segmentId)
                ->string($segment->businessCycleId()->toNative())
                    ->isEqualTo($businessCycleId)
                ->array($segment->name()->translations())
                    ->hasSize(2)
                    ->containsValues(['UN NOM DE SEGMENT', 'A SEGMENT NAME'])
                    ->hasKeys(['fr_FR', 'en_US'])
                ->array($segment->description()->translations())
                    ->hasSize(2)
                    ->containsValues(['UNE DESCRIPTION DU SEGMENT', 'A DESCRIPTION OF THE SEGMENT'])
                    ->hasKeys(['fr_FR', 'en_US'])
        ;
    }
}
```

## 7. create the command handler

The command handler is the class that will receive a command *dispatched* by the command bus. In our case, its role is to call the factory in order to get an instance of the `Segment` aggregate root instance and persist in db by asking the repository to do it. 

As you can see, the command handler has 2 dependencies: the `SegmentFactory` and the `Respository`. The ensure the respect of the SOLID principable, we will use the interfaces, respectively `Sandbox\BusinessCycle\Domain\Segment\Service\SegmentFactoryInterface` and `Cubiche\Domain\Repository\RepositoryInterface`.

In order to implement the `SegmentCommandHandler`, create a new class in `Sandbox\BusinessCycle\Domain\Segment\SegmentCommandHandler`

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment;
use Sandbox\BusinessCycle\Domain\Segment\Command\CreateSegmentCommand;
use Sandbox\BusinessCycle\Domain\Segment\Service\SegmentFactoryInterface;
use Cubiche\Domain\Repository\RepositoryInterface;

/**
 * SegmentCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var SegmentFactoryInterface
     */
    protected $factory;

    /**
     * SegmentCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     * @param SegmentFactoryInterface $factory
     */
    public function __construct(RepositoryInterface $repository, SegmentFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param CreateSegmentCommand $command
     */
    public function createSegment(CreateSegmentCommand $command)
    {
        $segment = $this->factory->create(
            $command->id(),
            $command->businessCycleId(),
            $command->translatedNames(),
            $command->translatedDescriptions()
        );

        $this->repository->persist($segment);
    }
}
```

The test class (`Sandbox\BusinessCycle\Domain\Tests\Units\Segment\SegmentCommandHandlerTests`) extends the basic `TestCase` of the current domain layer. We have to create a command, pass it to the command bus, and verify the repository contains the newly created aggregate root.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\Command\CreateSegmentCommand;
use Sandbox\BusinessCycle\Domain\Segment\Segment;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\BusinessCycle\Domain\Tests\Units\TestCase;

/**
 * SegmentCommandHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentCommandHandlerTests extends TestCase
{
    public function testCreateSegment()
    {
        $this
            ->given($segmentId = SegmentId::next())
            ->and(
                $command = new CreateSegmentCommand(
                    $segmentId->toNative(),
                    BusinessCycleId::nextUUIDValue(),
                    [
                        'fr_FR' => 'UN NOM DE SEGMENT',
                        'en_US' => 'A SEGMENT NAME',
                    ],
                    [
                        'fr_FR' => 'UNE DESCRIPTION DU SEGMENT',
                        'en_US' => 'A DESCRIPTION OF THE SEGMENT',
                    ]
                )
            )
            ->when($repository = $this->writeRepository(Segment::class))
            ->then()
                ->variable($repository->get($segmentId))
                    ->isNull()
                ->and()
                ->when($this->commandBus()->dispatch($command))
                ->then()
                    ->object($segment = $repository->get($segmentId))
                        ->isNotNull()
                        ->isInstanceOf(Segment::class)
                    ->string($segment->name()->toNative())
                        ->isEqualTo('A SEGMENT NAME')
                    ->array($segment->name()->translations())
                        ->hasSize(2)
                    ->string($segment->description()->toNative())
                        ->isEqualTo('A DESCRIPTION OF THE SEGMENT')
                    ->array($segment->description()->translations())
                        ->hasSize(2)
        ;
    }
}
```

If you run the test like this, you will notice an error telling you this: `Not found a handler for a given message named Sandbox\BusinessCycle\Domain\Segment\Command\CreateSegmentCommand`. This is normal; indeed, you need to register somewhere that the `SegmentCommandHandler` is responsible for the command `CreateSegmentCommand` dspatched to the command bus. In order to fix it, register the command handler in test `TestCase` class (`Sandbox\BusinessCycle\Domain\Tests\Units\TestCase`) of your domain layer.

```php
<?php
    // ...
    
	protected function commandHandlers()
    {
    	// ...

    	$segmentCommandHandler = new SegmentCommandHandler(
            $this->writeRepository(Segment::class),
            new SegmentFactory()
        );

        return [
        	// ...
        	CreateSegmentCommand::class => $segmentCommandHandler,
        ];
    }
```

As you can see, we instanciate a command handler and map each command to the right command handler.

If you run the test now, you should have any error.

## 8. Read model
The ReadModel is a class that extends `Cubiche\Domain\Model\Entity` and implements `Cubiche\Domain\EventSourcing\ReadModelInterface`. Most of the time, you need only one ReadModel which quite the same as the WriteModel (the aggregate root implemented earlier).

Create the `Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment` class by implementing the getters and the setters.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\ReadModel;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\Model\IdInterface;

/**
 * Segment class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Segment extends Entity implements ReadModelInterface
{
    /**
     * @var BusinessCycleId
     */
    protected $businessCycleId;

    /**
     * @var LocalizableString
     */
    protected $name;

    /**
     * @var LocalizableString
     */
    protected $description;

    /**
     * Segment constructor.
     *
     * @param SegmentId $segmentId
     * @param BusinessCycleId $businessCycleId
     * @param LocalizableString $name
     * @param LocalizableString $description
     */
    public function __construct(
        SegmentId $segmentId,
        BusinessCycleId $businessCycleId,
        LocalizableString $name,
        LocalizableString $description
    ) {
        parent::__construct($segmentId);

        $this->businessCycleId = $businessCycleId;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return IdInterface
     */
    public function segmentId()
    {
        return $this->id;
    }

    /**
     * @return BusinessCycleId
     */
    public function businessCycleId()
    {
        return $this->businessCycleId;
    }

    /**
     * @param BusinessCycleId $businessCycleId
     */
    public function setBusinessCycleId(BusinessCycleId $businessCycleId)
    {
        $this->businessCycleId = $businessCycleId;
    }

    /**
     * @return LocalizableString
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param LocalizableString $name
     */
    public function setName(LocalizableString $name)
    {
        $this->name = $name;
    }

    /**
     * @return LocalizableString
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @param LocalizableString $description
     */
    public function setDescription(LocalizableString $description)
    {
        $this->description = $description;
    }
}
```

The test class (`Sandbox\BusinessCycle\Domain\Tests\Units\Segment\ReadModel\SegmentTests`) extends the `Sandbox\Core\Domain\Tests\Units\ReadModel\ReadModelTestCase` class. This `ReadModelTestCase` class come with some basic tests for the getters. You just need to implement the method `getArguments`. Nevertheless, you still need to implement the setter tests.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment\ReadModel;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\Core\Domain\Tests\Units\ReadModel\ReadModelTestCase;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * SegmentTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentTests extends ReadModelTestCase
{
    protected function getArguments()
    {
        return [
            SegmentId::next(),
            BusinessCycleId::next(),
            LocalizableString::fromArray([
                'fr_FR' => 'UN NOM DE SEGMENT',
                'en_US' => 'A SEGMENT NAME',
            ]),
            LocalizableString::fromArray([
                'fr_FR' => 'UN DESCRIPTION DU SEGMENT',
                'en_US' => 'A DESCRIPTION OF THE SEGMENT',
            ]),
        ];
    }

    public function testSetBusinessCycleId()
    {
        $businessCycleId = BusinessCycleId::next();
        $anotherBusinessCycleId = BusinessCycleId::next();

        $args = [
            SegmentId::next(),
            $businessCycleId,
            LocalizableString::fromArray([
                'fr_FR' => 'UN NOM DE SEGMENT',
                'en_US' => 'A SEGMENT NAME',
            ]),
            LocalizableString::fromArray([
                'fr_FR' => 'UN DESCRIPTION DU SEGMENT',
                'en_US' => 'A DESCRIPTION OF THE SEGMENT',
            ]),
        ];

        $this
            ->given($segment = $this->createReadModel($args))
            ->then()
                ->string($segment->businessCycleId()->toNative())
                    ->isEqualTo($businessCycleId->toNative())
                ->and()
                ->when($segment->setBusinessCycleId($anotherBusinessCycleId))
                ->then()
                    ->string($segment->businessCycleId()->toNative())
                        ->isEqualTo($anotherBusinessCycleId->toNative())
        ;
    }

    public function testSetName()
    {
        $names = [
            'fr_FR' => 'UN NOM DE SEGMENT',
            'en_US' => 'A SEGMENT NAME',
        ];

        $descriptions = [
            'fr_FR' => 'UN DESCRIPTION DU SEGMENT',
            'en_US' => 'A DESCRIPTION OF THE SEGMENT',
        ];

        $args = [
            SegmentId::next(),
            BusinessCycleId::next(),
            LocalizableString::fromArray($names),
            LocalizableString::fromArray($descriptions),
        ];

        $this
            ->given($segment = $this->createReadModel($args))
            ->then()
                ->string($segment->name()->toNative())
                    ->isEqualTo($names['en_US'])
                ->array($segment->name()->translations())
                    ->containsValues(array_values($names))
                ->and()
                ->when(
                    $segment->setName(
                        LocalizableString::fromArray([
                            'en_US' => 'BEST SELLER AGENCIES',
                            'fr_FR' => 'AGENCES LES PLUS VENDEUSES',
                        ])
                    )
                )
                ->then()
                    ->string($segment->name()->toNative())
                        ->isEqualTo('BEST SELLER AGENCIES')
                    ->array($segment->name()->translations())
                        ->containsValues(['BEST SELLER AGENCIES', 'AGENCES LES PLUS VENDEUSES'])
        ;
    }

    public function testSetDescription()
    {
        $names = [
            'fr_FR' => 'UN NOM DE SEGMENT',
            'en_US' => 'A SEGMENT NAME',
        ];

        $descriptions = [
            'fr_FR' => 'UN DESCRIPTION DU SEGMENT',
            'en_US' => 'A DESCRIPTION OF THE SEGMENT',
        ];

        $args = [
            SegmentId::next(),
            BusinessCycleId::next(),
            LocalizableString::fromArray($names),
            LocalizableString::fromArray($descriptions),
        ];

        $this
            ->given($segment = $this->createReadModel($args))
            ->then()
                ->string($segment->description()->toNative())
                    ->isEqualTo($descriptions['en_US'])
                ->array($segment->description()->translations())
                    ->containsValues(array_values($descriptions))
                ->and()
                ->when(
                    $segment->setDescription(
                        LocalizableString::fromArray([
                            'en_US' => 'THE AGENCIES THAT HAVE SOLD THE MOST LAST YEAR',
                            'fr_FR' => 'LES AGENCES QUI ONT VENDU LE PLUS L\'ANNEE PASSEE',
                        ])
                    )
                )
                ->then()
                    ->string($segment->description()->toNative())
                        ->isEqualTo('THE AGENCIES THAT HAVE SOLD THE MOST LAST YEAR')
                    ->array($segment->description()->translations())
                        ->containsValues(['THE AGENCIES THAT HAVE SOLD THE MOST LAST YEAR', 'LES AGENCES QUI ONT VENDU LE PLUS L\'ANNEE PASSEE'])
        ;
    }
}
```

## 9. Create the projector
The projector is in charge of creating a readmodel and persist it in a database. The projector is listening to the event `SegmentWasCreated`, so it needs to implement a method with name with this format `when[EVENT NAME]`, like `whenSegmentWasCreated` (this is just a convention). This method will receive the event as argument.

Next to that, the projector should implement a static method (declared in the `DomainEventSubscriberInterface` interface implemented by the projector) called `getSubscribedEvents` which is an array returning the list of listened events and the project method.

The projector should be created at `Sandbox\BusinessCycle\Domain\Segment\ReadModel\Projection\SegmentProjector`:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\ReadModel\Projection;

use Sandbox\BusinessCycle\Domain\Segment\Event\SegmentWasCreated;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment;
use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * SegmentProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentProjector implements DomainEventSubscriberInterface
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $repository;

    /**
     * SegmentProjector constructor.
     *
     * @param QueryRepositoryInterface $repository
     */
    public function __construct(QueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param SegmentWasCreated $event
     */
    public function whenSegmentWasCreated(SegmentWasCreated $event)
    {
        $segment = new Segment(
            $event->segmentId(),
            $event->businessCycleId(),
            $event->name(),
            $event->description(),
        );

        $this->repository->persist($segment);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            SegmentWasCreated::class => ['whenSegmentWasCreated', 250],
        ];
    }
}
```

The test class should extend the basic `TestCase` class. The test should validate that when a command is dispatch, the projection is properly created by the projector.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment\ReadModel\Projection;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\Command\CreateSegmentCommand;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Projection\SegmentProjector;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\BusinessCycle\Domain\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\ReadModelInterface;

/**
 * SegmentProjector class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentProjectorTests extends TestCase
{
    public function testCreate()
    {
        $this
            ->given(
                $projector = new SegmentProjector(
                    $this->queryRepository(Segment::class)
                )
            )
            ->then()
                ->array($projector->getSubscribedEvents())
                    ->isNotEmpty()
        ;
    }

    public function testWhenSegmentWasCreated()
    {
        $this
            ->given($repository = $this->queryRepository(Segment::class))
            ->and(
                $segmentId = SegmentId::next(),
                $businessCycleId = BusinessCycleId::next(),
                $command = new CreateSegmentCommand(
                    $segmentId->toNative(),
                    $businessCycleId->toNative(),
                    [
                        'fr_FR' => 'UN NOM DE SEGMENT',
                        'en_US' => 'A SEGMENT NAME',
                    ],
                    [
                        'fr_FR' => 'UNE DESCRIPTION DU SEGMENT',
                        'en_US' => 'A DESCRIPTION OF THE SEGMENT',
                    ]
                )
            )
            ->then()
                ->boolean($repository->isEmpty())
                    ->isTrue()
                ->and()
                ->when($this->commandBus()->dispatch($command))
                ->then()
                    ->boolean($repository->isEmpty())
                        ->isFalse()
                    ->object($repository->get($segmentId))
                        ->isInstanceOf(ReadModelInterface::class)
        ;
    }
}
```

If you run the tests like this, it won't succeed. It is because we forgot to register the projector to the TestCase scenario. Let's fix it; update the `Sandbox\BusinessCycle\Domain\Tests\Unit\TestCase` class by adding the projector to the `eventSubscribers` method:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units;

// ...
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment as ReadModelSegment;
// ...

/**
 * TestCase class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TestCase extends BaseTestCase
{
	// ...

	/**
     * @return array
     */
    protected function eventSubscribers()
    {
        // ...

        $segmentProjector = new SegmentProjector(
            $this->queryRepository(ReadModelSegment::class)
        );

        return [
            // ...
            $segmentProjector,
        ];
    }
}

```

## 10. Create query
Query is an implementation of `MessageInterface`. Query will be send to the `QueryBus`. It is a representation of a request to obtain a read model. 

Query might contain parameter. This is the case of our `FindAllSegments` query. The parameter is the `businessCycleId` for which we are searching the Segment.

As commands, queries might be validate by a validator. In that case, you need to implement the `loadValidatorMetaData` method.

Here is the implementation of our `Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments` query:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query;

use Cubiche\Core\Cqrs\Query\Query;
use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * FindAllSegments class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllSegments extends Query
{
    /**
     * @var string
     */
    protected $businessCycleId;

    /**
     * FindAllSegments constructor.
     *
     * @param string $businessCycleId
     */
    public function __construct($businessCycleId)
    {
        $this->businessCycleId = $businessCycleId;
    }

    /**
     * @return string
     */
    public function businessCycleId()
    {
        return $this->businessCycleId;
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    public static function loadValidatorMetaData(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('businessCycleId', Assert::uuid()->notBlank());
    }
}
```

The test class should extend the `QueryTestCase` class by implementing the `getArguments` and `validatorProvider` methods.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment\ReadModel\Query;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\Core\Domain\Tests\Units\ReadModel\Query\QueryTestCase;

/**
 * FindAllSegmentsTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllSegmentsTests extends QueryTestCase
{
    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return [
            BusinessCycleId::nextUUIDValue(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function validatorProvider()
    {
        return [
            [
                'assert' => true,
                'arguments' => $this->getArguments(),
            ],
            [
                'assert' => false,
                'arguments' => [
                    'NOT-UUID',
                ],
            ],
            [
                'assert' => false,
                'arguments' => [
                    '',
                ],
            ],
        ];
    }
}
```

## 11. QueryHandler

The query handler is responsible of handling request that have been dispatched by the QueryBus. One of the method of the query bus will receive the query as argument and will be responsible of building a query inside the read model repository.

Here is the implementation of our `SegmentQueryHandler` placed in `src/Sandbox/BusinessCycle/Domain/Segment/ReadModel/SegmentQueryHandler.php`

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Segment\ReadModel;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments;
use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * SegmentQueryHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentQueryHandler
{
    /**
     * @var QueryRepositoryInterface
     */
    protected $queryRepository;

    /**
     * SegmentQueryHandler constructor.
     *
     * @param QueryRepositoryInterface $queryRepository
     */
    public function __construct(QueryRepositoryInterface $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    /**
     * @param FindAllSegments $query
     *
     * @return CollectionInterface
     */
    public function findAllSegments(FindAllSegments $query)
    {
        return $this->queryRepository->find(
            Criteria::property('businessCycleId')->eq(
                BusinessCycleId::fromNative($query->businessCycleId())
            )
        );
    }
}
```

The test file is extending the basic `TestCase` class of our domain layer. The test has to prove that when we dispatch a query to the querybus, we obtain a read model in response.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units\Segment\ReadModel;

use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\BusinessCycle\Domain\Tests\Units\TestCase;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * SegmentQueryHandlerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentQueryHandlerTests extends TestCase
{
    protected function addSegmentToRepository(SegmentId $segmentId, BusinessCycleId $businessCycleId, $name, $description)
    {
        $repository = $this->queryRepository(Segment::class);

        $repository->persist(
            new Segment(
                $segmentId,
                $businessCycleId,
                LocalizableString::fromArray([
                    'fr_FR' => '[FR] '.$name,
                    'en_US' => '[EN] '.$name,
                ]),
                LocalizableString::fromArray([
                    'fr_FR' => '[FR] '.$description,
                    'en_US' => '[EN] '.$description,
                ])
            )
        );
    }

    public function testFindAllSegments()
    {
        $this
            ->given(
                $businessCycleId = BusinessCycleId::next(),
                $query = new FindAllSegments($businessCycleId->toNative())
            )
            ->then()
                ->array(iterator_to_array($this->queryBus()->dispatch($query)))
                    ->isEmpty()
                ->and()
                ->when(
                    $this->addSegmentToRepository(
                        SegmentId::next(),
                        $businessCycleId,
                        'A SEGMENT NAME',
                        'A DESCRIPTION OF THE SEGMENT'
                    )
                )
                ->and(
                    $this->addSegmentToRepository(
                        SegmentId::next(),
                        $businessCycleId,
                        'A SECOND SEGMENT NAME',
                        'A DESCRIPTION OF THE SECOND SEGMENT'
                    )
                )
                ->and(
                    $this->addSegmentToRepository(
                        SegmentId::next(),
                        BusinessCycleId::next(),
                        'A THIRD SEGMENT NAME',
                        'A DESCRIPTION OF THE THIRD SEGMENT'
                    )
                )
                ->then()
                    ->array(iterator_to_array($this->queryBus()->dispatch($query)))
                        ->isNotEmpty()
                        ->hasSize(2)
        ;
    }
}
```

If you run the tests like this, you will get a message saying `Not found a handler for a given message named Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments`. This is because we forgot to register the query handler inside the `Sandbox\BusinessCycle\Domain\Tests\Units\TestCase` class. Let's fix it:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Domain\Tests\Units;

//...
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\SegmentQueryHandler;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment as ReadModelSegment;
// ...

/**
 * TestCase class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TestCase extends BaseTestCase
{
	// ...

	/**
     * @return array
     */
    protected function queryHandlers()
    {
        // ...

        $segmentQueryHandler = new SegmentQueryHandler(
            $this->queryRepository(ReadModelSegment::class)
        );

        // ...

        return [
            // ...
            FindAllSegments::class => $segmentQueryHandler,
        ];
    }

    // ...
}
```

## 12. create write model controller (CommandController)

For the moment, we finish to implement the model layer. It is time to attack the application layer. Let's start by the write model controller. 

According to the book "DDD in PHP", "Application is the thin layer that connects clients from outside to your Domain [...] The Application layer is the area that separates the Domain Model from the clients that query or change its state." [^4]
[^4]: see chapter 11 of "DDD in PHP". 

The write model controller, also called command controller is in charge of instanciating a command and dispatching it to the CommandBus. This class must extends the `Sandbox\Core\Application\Controller\CommandController`. Let's see what it gives for the `SegmentController` placed in `src/Sandbox/BusinessCycle/Application/Segment/Controller/SegmentController.php`

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Application\Segment\Controller;

use Sandbox\BusinessCycle\Domain\Segment\Command\CreateSegmentCommand;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Sandbox\Core\Application\Controller\CommandController;

/**
 * SegmentController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentController extends CommandController
{
	/**
     * @param string $businessCycleId
     * @param array $translatedNames
     * @param array $translatedDescriptions
     *
     * @return string
     */
    public function createAction($businessCycleId, $translatedNames, $translatedDescriptions)
    {
        $segmentId = SegmentId::nextUUIDValue();
        $this->commandBus()->dispatch(
            new CreateSegmentCommand($segmentId, $businessCycleId, $translatedNames, $translatedDescriptions)
        );

        return $segmentId;
    }
}
```

The test class is also quite simple. It should extend the `TestCase` class but the one from the Application Layer (`Sandbox\BusinessCycle\Application\Tests\Units\TestCase`).

The tests should prove that when we dispatch a command, we can retrive the newly created object.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Application\Tests\Units\Segment\Controller;

use Sandbox\BusinessCycle\Application\Segment\Controller\SegmentController;
use Sandbox\BusinessCycle\Application\Tests\Units\TestCase;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments;

/**
 * SegmentControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentControllerTests extends TestCase
{
    protected function createController()
    {
        return new SegmentController($this->commandBus());
    }

    protected function findAllSegments($businessCycleId)
    {
        return $this->queryBus()->dispatch(new FindAllSegments($businessCycleId));
    }

    public function testCreateAction()
    {
        $this
            ->given($controller = $this->createController())
            ->and($businessCycleId = $this->faker->uuid())
            ->and($names = [
                'fr_FR' => $this->faker->sentence(3),
                'en_US' => $this->faker->sentence(3),
            ])
            ->and($descriptions = [
                'fr_FR' => $this->faker->sentence(3),
                'en_US' => $this->faker->sentence(3),
            ])
            ->when($segments = $this->findAllSegments($businessCycleId))
                ->array(iterator_to_array($segments))
                    ->isEmpty()
                ->and()
                ->when($segmentId = $this->createController()->createAction($businessCycleId, $names, $descriptions))
                ->then()
                    ->string($segmentId)
                        ->isNotEmpty()
                    ->array(iterator_to_array($this->findAllSegments($businessCycleId)))
                        ->isNotEmpty()
                            ->hasSize(1)
        ;
    }
}
```

## 13. create the read model controller (QueryController)

The `SegmentController` for the ReadModel should extend the `Sandbox\Core\Application\Controller\QueryController` class. The query controller method should instanciate a Query and dispatch it to the QueryBus.

Here is an example of the `Sandbox\BusinessCycle\Application\Segment\ReadModel\Controller\SegmentController` implementation:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Application\Segment\ReadModel\Controller;

use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment;
use Sandbox\Core\Application\Controller\QueryController;

/**
 * SegmentController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentController extends QueryController
{
    /**
     * @param $businessCycleId
     *
     * @return Segment[]
     */
    public function findAllAction($businessCycleId)
    {
        return $this->queryBus()->dispatch(
            new FindAllSegments($businessCycleId)
        );
    }
}
```

The test file should extends the `TestCase` class of the Application layer and should validate it is possible to get object from the read model database.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Application\Tests\Units\Segment\ReadModel\Controller;

use Sandbox\BusinessCycle\Application\Segment\ReadModel\Controller\SegmentController;
use Sandbox\BusinessCycle\Application\Tests\Units\TestCase;
use Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId;
use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment;
use Sandbox\BusinessCycle\Domain\Segment\SegmentId;
use Cubiche\Domain\Localizable\LocalizableString;

/**
 * SegmentControllerTests class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentControllerTests extends TestCase
{
    protected function createController()
    {
        return new SegmentController($this->queryBus());
    }

    public function testFindAllAction()
    {
        $this
            ->given($controller = $this->createController())
            ->and($repository = $this->queryRepository(Segment::class))
            ->and($businessCycleId = BusinessCycleId::next())
            ->then()
                ->array(iterator_to_array($controller->findAllAction($businessCycleId)))
                    ->isEmpty()
                ->and()
                ->when(
                    $repository->persist(
                        new Segment(
                            SegmentId::next(),
                            $businessCycleId,
                            LocalizableString::fromArray([
                                'fr_FR' => 'UN NOM DE SEGMENT',
                            ]),
                            LocalizableString::fromArray([
                                'fr_FR' => 'UNE DESCRIPTION DU SEGMENT',
                            ])
                        )
                    )
                )
                ->and(
                    $repository->persist(
                        new Segment(
                            SegmentId::next(),
                            $businessCycleId,
                            LocalizableString::fromArray([
                                'fr_FR' => 'UN SECOND NOM DE SEGMENT',
                            ]),
                            LocalizableString::fromArray([
                                'fr_FR' => 'UNE DESCRIPTION DU SECOND SEGMENT',
                            ])
                        )
                    )
                )
                ->and(
                    $repository->persist(
                        new Segment(
                            SegmentId::next(),
                            BusinessCycleId::next(),
                            LocalizableString::fromArray([
                                'fr_FR' => 'UN TROISIEME NOM DE SEGMENT',
                            ]),
                            LocalizableString::fromArray([
                                'fr_FR' => 'UNE DESCRIPTION DU TROISIEME SEGMENT',
                            ])
                        )
                    )
                )
                ->then()
                    ->array(iterator_to_array($controller->findAllAction($businessCycleId->toNative())))
                        ->isNotEmpty()
                        ->hasSize(2)
        ;
    }
}
```

## 14. Creating the Infrastructure Layer

It is time to start working on the Infrastructure layer. Test part is not yet covered by test. This will come with Behat we will implement later.

First class to create is the `Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\SegmentType` which represents/maps the fields of our read model to graphql. This class extends the `Youshido\GraphQL\Type\Object\AbstractObjectType` class.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL;

use Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * SegmentType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentType extends AbstractObjectType
{

    public function build($config)
    {
        $config
            ->addField('id', [
                'type' => new NonNullType(new IdType()),
                'resolve' => function (Segment $segment, $args) {
                    return $segment->id()->toNative();
                },
            ])
            ->addField('name', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Segment $segment, $args) {
                    return $segment->name()->toNative();
                },
            ])
            ->addField('description', [
                'type' => new NonNullType(new StringType()),
                'resolve' => function (Segment $segment, $args) {
                    return $segment->description()->toNative();
                },
            ])
        ;
    }
}
```

Before being able to code our mutation, we need to create the input type for the name and the description. Indeed, for each of them, we need an array with the locale code and the translation of the name or description in that local. In order to do that, we need to create two classes extending the `Youshido\GraphQL\Type\InputObject\AbstractInputObjectType` : 

- `Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\SegmentNameInputType`:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL;

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * SegmentNameInputType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentNameInputType extends AbstractInputObjectType
{
    public function build($config)
    {
        $config
            ->addField('code', new NonNullType(new StringType()))
            ->addField('name', new NonNullType(new StringType()))
        ;
    }
}
```


- `Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\SegmentDescriptionInputType`:

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL;

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * SegmentDescriptionInputType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SegmentDescriptionInputType extends AbstractInputObjectType
{
    public function build($config)
    {
        $config
            ->addField('code', new NonNullType(new StringType()))
            ->addField('description', new NonNullType(new StringType()))
        ;
    }
}
```

Then your are ready create your mutation. Our mutation `Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\Mutation\CreateSegment` extends the `Youshido\GraphQL\Field\AbstractField` class. It should implement a few method :
- `build` which configure the arguments of the mutation
- `resolve` which implement the resolution of the mutation (calling the write model controller with the arguments above and return a read model)
- `getName` which returns the name of the mutation that will be used by the end user
- `getType` which return the type of the response returned by the resolver

Here is the implementation of our mutation for the segment:
```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\Mutation;

use Sandbox\BusinessCycle\Application\Segment\Controller\SegmentController;
use Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\SegmentDescriptionInputType;
use Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\SegmentNameInputType;
use Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\SegmentType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Sandbox\BusinessCycle\Application\Segment\ReadModel\Controller\SegmentController as ReadModelSegmentController;

/**
 * CreateSegment class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CreateSegment extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('businessCycleId', new NonNullType(new IdType()))
            ->addArgument('name', new NonNullType(new ListType(new SegmentNameInputType())))
            ->addArgument('description', new NonNullType(new ListType(new SegmentDescriptionInputType())))
        ;
    }

    /**
     * @param $value
     * @param array $args
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var SegmentController $controller */
        $controller = $info->getContainer()->get('app.controller.segment');

        $nameTranslations = [];
        array_walk($args['name'], function ($item) use (&$nameTranslations) {
            $nameTranslations[$item['code']] = $item['name'];
        });

        $descriptionTranslations = [];
        array_walk($args['description'], function ($item) use (&$descriptionTranslations) {
            $descriptionTranslations[$item['code']] = $item['description'];
        });

        $controller->createAction($args['businessCycleId'], $nameTranslations, $descriptionTranslations);

        /** @var ReadModelSegmentController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.segment');

        return $controller->findAllAction($args['businessCycleId']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'createSegment';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new SegmentType());
    }
}
```

Then you can implement the GraphQL query. The query class should extend `Youshido\GraphQL\Field\AbstractField` and should implement the methods as the mutation.

```php
<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\Query;

use Sandbox\BusinessCycle\Application\Segment\ReadModel\Controller\SegmentController;
use Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\SegmentType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;

/**
 * FindAllSegment class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class FindAllSegment extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function build(FieldConfig $config)
    {
        $config
            ->addArgument('businessCycleId', new NonNullType(new IdType()))
        ;    
    }

    /**
     * @param $value
     * @param array $args
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var SegmentController $controller */
        $controller = $info->getContainer()->get('app.controller.read_model.segment');
        
        return $controller->findAllAction($args['businessCycleId']);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return new ListType(new SegmentType());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'segments';
    }
}
```

In order to be able to test it inside GraphiQL, we need to update some configuration. 

- add the mapping configuration inside `app/config/cubiche.yml`:
```
cubiche_core:
	// ...
	mongodb:
		// ...
		document_manager:
			// ...
			mappings:
				// ...
				segment:
                    type: xml
                    prefix: Sandbox\BusinessCycle\Domain\Segment\ReadModel
                    dir: "%kernel.root_dir%/config/mapping/BusinessCycle/Segment"
                    separator: /
```
- create the mapping file for our read model `app/config/mapping/BusinessCycle/Segment/Segment.xml`:
```xml
<cubiche-mapping>

    <aggregate-root name="Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment" collection="segment">
        <field fieldName="id" id="true" type="Sandbox\BusinessCycle\Domain\Segment\SegmentId" />
        <field fieldName="businessCycleId" type="Sandbox\BusinessCycle\Domain\BusinessCycle\BusinessCycleId" />
        <field fieldName="name" type="LocalizableString" />
        <field fieldName="description" type="LocalizableString" />
    </aggregate-root>
    
</cubiche-mapping>
```

- configure the segment controller: 
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="app.controller.segment" class="Sandbox\BusinessCycle\Application\Segment\Controller\SegmentController">
            <argument type="service" id="cubiche.command_bus" />
        </service>

        <service id="app.controller.read_model.segment" class="Sandbox\BusinessCycle\Application\Segment\ReadModel\Controller\SegmentController">
            <argument type="service" id="cubiche.query_bus" />
        </service>
    </services>
</container>
```
- configure the listeners:
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!--  listener  -->

        <!--  subscriber  -->

    </services>
</container>
```
- configure the repository:
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!--  write model  -->
        <service id="app.repository.segment" class="Cubiche\Domain\EventSourcing\AggregateRepository">
            <factory service="cubiche.event_store.aggregate_repository_factory" method="create" />
            <argument>Sandbox\BusinessCycle\Domain\Segment\Segment</argument>
        </service>

        <!--  read model  -->
        <service id="app.query_repository.segment" class="Cubiche\Infrastructure\Repository\MongoDB\DocumentQueryRepository">
            <factory service="cubiche.repository.mongodb.document_query_repository_factory" method="create" />
            <argument>Sandbox\BusinessCycle\Domain\Segment\ReadModel\Segment</argument>
        </service>
    </services>
</container>
```
- configure the other services:
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <!-- factory -->
        <service id="app.factory.segment" class="Sandbox\BusinessCycle\Domain\Segment\Service\SegmentFactory" />

        <!-- projector -->
        <service id="app.segment.projector" class="Sandbox\BusinessCycle\Domain\Segment\ReadModel\Projection\SegmentProjector" >
            <argument type="service" id="app.query_repository.segment"/>

            <tag name="cubiche.domain.event_subscriber"/>
        </service>

        <!-- command handler -->
        <service id="app.segment.command_handler" class="Sandbox\BusinessCycle\Domain\Segment\SegmentCommandHandler">
            <argument type="service" id="app.repository.segment"/>
            <argument type="service" id="app.factory.segment"/>

            <tag name="cubiche.command_handler" class="Sandbox\BusinessCycle\Domain\Segment\Command\CreateSegmentCommand" />
        </service>

        <!-- query handler -->
        <service id="app.segment.query_handler" class="Sandbox\BusinessCycle\Domain\Segment\ReadModel\SegmentQueryHandler">
            <argument type="service" id="app.query_repository.segment"/>

            <tag name="cubiche.query_handler" class="Sandbox\BusinessCycle\Domain\Segment\ReadModel\Query\FindAllSegments" />
        </service>

        <!-- command validator -->
    </services>
</container>
```

and then finally, register the mutation and the query on the schema of your GraphQL application by updating the `Sandbox\Core\Infrastructure\GraphQL\ApplicationSchema` :
```php
<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Infrastructure\GraphQL;

// ...
use Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\Mutation\CreateSegment;
use Sandbox\BusinessCycle\Infrastructure\Segment\GraphQL\Query\FindAllSegment;

/**
 * ApplicationSchema class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ApplicationSchema extends Schema
{
    public function build(SchemaConfig $config)
    {
        $config->getQuery()->addFields([
            // ...
            new FindAllSegment(),
        ]);

        $config->getMutation()->addFields([
            // ...
            new CreateSegment(),
        ]);
    }
}

```

