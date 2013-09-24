<?php
/**
 * This auto-loads the autoloader, auto-loading the php-opencloud
 * namespaces
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('OpenCloud', __DIR__);