<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->objectStoreV1();

$container = $service->getContainer('{containerName}');

$container->resetMetadata([
    '{key_1}' => '{val_1}',
    '{key_2}' => '{val_2}',
]);
