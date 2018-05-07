<?php

namespace Sandbox\Security\Application\Tests\Units;

use Sandbox\Core\Application\Controller\QueryController;
use Cubiche\Core\Cqrs\Query\QueryBus;

abstract class QueryControllerTestCase extends TestCase
{
    /**
     * @return QueryController
     */
    abstract protected function createController();

    /**
     * Test QueryBus method.
     */
    public function testQueryBus()
    {
        $this
            ->given($controller = $this->createController())
            ->then()
                ->object($controller->queryBus())
                    ->isInstanceOf(QueryBus::class)
        ;
    }
}
