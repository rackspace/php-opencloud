<?php

$currentDir = dirname(__FILE__);

require_once($currentDir . '/../Autoload.php');

$classLoader = new SplClassLoader('OpenCloud', $currentDir . '/../lib');
$classLoader->register();
    
?>