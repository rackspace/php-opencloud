<?php

require __DIR__ . '/../../vendor/autoload.php';

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, [
    'username' => 'jamiehannaford1',
    'apiKey'   => '8d28b0ee0c694a6d9db6e973ebfb2d67'
]);

$service = $client->computeService();

var_dump($service->limits());