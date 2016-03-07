<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

foreach ($service->listKeypairs() as $keypair) {
    /** @var $keypair Rackspace\Compute\v2\Models\Keypair */
}