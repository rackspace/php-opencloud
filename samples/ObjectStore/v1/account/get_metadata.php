<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->objectStoreV1();

$account = $service->getAccount();
$metadata = $account->getMetadata();
