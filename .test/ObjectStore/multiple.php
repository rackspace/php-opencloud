<?php

require __DIR__ . '/../../vendor/autoload.php';

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, [
    'username' => 'jamiehannaford1',
    'apiKey'   => '8d28b0ee0c694a6d9db6e973ebfb2d67'
]);

$service = $client->objectStoreService('cloudFiles', 'DFW');

$container = $service->getContainer(1);

$file = 'TestObject_1';
$files = array(
    array('name' => $file . '.txt', 'path' => __DIR__ . '/../../tests/OpenCloud/Smoke/Resource/ObjectStore/' . $file)
);

$responses = $container->uploadObjects($files);

foreach ($responses as $response) {
    var_dump($response->getBody());
}