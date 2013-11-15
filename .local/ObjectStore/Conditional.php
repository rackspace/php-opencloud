<?php

require __DIR__ . '/../../lib/php-opencloud.php';

use OpenCloud\Rackspace;

$client = new Rackspace(RACKSPACE_US, array(
    'username' => 'jamiehannaford1',
    'apiKey'   => '504de62cad6b2356d378027e40d25d7c'
));

echo RACKSPACE_US;die;

$service = $client->objectStoreService('cloudFiles');

$container = $service->getContainer('1');

$file = $container->getObject('file_1', array(
    'Range' => 'bytes=-20'
));

var_dump($file->getContentLength());