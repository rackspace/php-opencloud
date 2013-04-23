<?php
/**
 * entry point for PHP-OpenCloud library
 */
require_once(dirname(__FILE__) . '/Autoload.php');
$classLoader = new SplClassLoader('OpenCloud', dirname(__FILE__));
$classLoader->register();
