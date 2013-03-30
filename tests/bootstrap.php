<?php

require_once 'Autoload.php';

$classLoader = new SplClassLoader('OpenCloud', dirname(__FILE__) . '/../lib');
$classLoader->register();