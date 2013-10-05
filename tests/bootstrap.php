<?php
/**
 * This auto-loads the autoloader, auto-loading the php-opencloud
 * namespaces
 */

require_once __DIR__ . '/../lib/OpenCloud/Globals.php';

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('OpenCloud', __DIR__);