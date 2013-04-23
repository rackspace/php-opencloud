<?php
/**
 * entry point for PHP-OpenCloud library
 */
require_once(dirname(__FILE__) . '/Autoload.php');
require_once(dirname(__FILE__) . '/OpenCloud/Globals.php');
$classLoader = new SplClassLoader('OpenCloud', dirname(__FILE__));
$classLoader->register();
