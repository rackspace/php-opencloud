<?php

require __DIR__ . '/../../vendor/autoload.php';

use OpenCloud\Rackspace;
use OpenCloud\Common\Collection\ResourceList;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, [
    'username' => 'jamiehannaford1',
    'apiKey'   => '8d28b0ee0c694a6d9db6e973ebfb2d67'
]);

$service = $client->objectStoreService('cloudFiles', 'ORD');

$container = $service->getContainer('ORD_TEST_1');

$list = ResourceList::factory($container, $container->getUrl(), 'DataObject', 'name');

$i = 1;

foreach ($list as $bucket) {
var_dump($bucket);die;
    //echo $bucket->instantiateCurrentResource()->getName(), ' --- ', $i, PHP_EOL;

    $i++;
}