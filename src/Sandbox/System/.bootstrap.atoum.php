<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$loader = require __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../vendor/atoum/atoum/scripts/runner.php';

$loader->setPsr4('Sandbox\\', '');
$loader->addPsr4('Sandbox\Core\\', __DIR__.'/../Core');
$loader->addPsr4('Sandbox\System\\', __DIR__);
