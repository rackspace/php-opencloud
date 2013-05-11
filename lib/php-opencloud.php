<?php
/**
 * entry point for PHP-OpenCloud library
 */
require_once(__DIR__ . '/Autoload.php');
require_once(__DIR__ . '/OpenCloud/Globals.php');

$classLoader = new SplClassLoader('OpenCloud', __DIR__);
$classLoader->register();