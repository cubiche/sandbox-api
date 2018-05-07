<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use Cubiche\Infrastructure\MongoDB\DocumentManager;
use MongoDB\Database;

/**
 * CubicheDatabaseContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
final class CubicheDatabaseContext implements Context
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var Database
     */
    private $eventStoreDatabase;

    /**
     * CubicheDatabaseContext constructor.
     *
     * @param DocumentManager $documentManager
     * @param Connection      $eventStoreConnection
     */
    public function __construct(DocumentManager $documentManager, Connection $eventStoreConnection)
    {
        $this->documentManager = $documentManager;
        $this->eventStoreDatabase = new Database($eventStoreConnection->manager(), $eventStoreConnection->database());
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        // drop read model databases
        $schemaManager = $this->documentManager->getSchemaManager();
        $schemaManager->dropDatabases();

        // drop write model event store
        $this->eventStoreDatabase->drop();
    }
}
