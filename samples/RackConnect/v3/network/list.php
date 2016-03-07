<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->rackConnectV3(['region' => '{region}']);

foreach ($service->listNetwork('{id}') as $network) {
    /** @var $network Rackspace\RackConnect\v3\Models\Network */
}