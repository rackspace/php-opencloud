<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

/** @var \Rackspace\Compute\v2\Models\Keypair $keypair */
$keypair = $service->getKeypair('{name}');
$keypair->delete();