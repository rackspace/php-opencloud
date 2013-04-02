<?php

$currentPath = dirname(__FILE__);

require_once $currentPath . '/../Autoload.php';

$classLoader = new SplClassLoader('OpenCloud', $currentPath . '/../lib');
$classLoader->register();