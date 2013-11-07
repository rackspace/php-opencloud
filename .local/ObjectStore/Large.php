<?php

require __DIR__ . '/../../lib/php-opencloud.php';

use OpenCloud\Rackspace;

$client = new Rackspace(RACKSPACE_US, array(
    'username' => 'jamiehannaford1',
    'apiKey'   => '504de62cad6b2356d378027e40d25d7c'
));

$service = $client->objectStoreService('cloudFiles');

$container = $service->getContainer('1');

//$transfer = $container->setupObjectTransfer(array(
//    'name' => 'blah blah.txt',
//    'path' => '/Users/jami6682/Downloads/IMG_2744.JPG'
//));
//
//$transfer->upload();

//$object = $container->getObject('blah blah.txt');
//$object->purge('jamie@limetree.org');

//$containerList = $service->listContainers();
//while (($limitedContainer = $containerList->next()) !== false) {
//    var_dump($limitedContainer->getName(), $limitedContainer->getObjectCount());
//}

$service->bulkDelete(array('1/file_3', '1/file_21'));