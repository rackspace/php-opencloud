<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->objectStoreV1();

$account = $service->getAccount();

$account->mergeMetadata([
    '{key_1}' => '{val_1}',
    '{key_2}' => '{val_2}',
]);
