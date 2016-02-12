<?php

require 'vendor/autoload.php';

$openstack = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

foreach ($service->listKeypair('{id}') as $keypair) {
    /** @var $keypair Rackspace\Compute\v2\Models\Keypair */
}