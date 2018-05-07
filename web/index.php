<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

$env = isset($_SERVER['SYMFONY_ENV']) ? $_SERVER['SYMFONY_ENV'] : 'prod';
switch ($env) {
    case 'prod':
        $debug = false;
        break;
    case 'test':
        if (isset($_SERVER['HTTP_CLIENT_IP'])
            || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            || php_sapi_name() === 'cli-server'
            || !IpUtils::checkIp(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '192.168.0.0/16', '172.17.0.0/16'))
        ) {
            header('HTTP/1.0 403 Forbidden');
            exit('You are not allowed to access this file.');
        }
    case 'dev':
        $debug = true;
        if (isset($_SERVER['HTTP_CLIENT_IP'])
            || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            || php_sapi_name() === 'cli-server'
            || !IpUtils::checkIp(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '192.168.0.0/16', '172.17.0.0/16'))
        ) {
            header('HTTP/1.0 403 Forbidden');
            exit('You are not allowed to access this file.');
        }
        Debug::enable();
        break;
    default:
        $debug = false;
        break;
}

$kernel = new AppKernel($env, $debug);
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the
// configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
