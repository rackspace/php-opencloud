<?php

require 'vendor/autoload.php';

$openstack = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

foreach ($service->listServer('{id}') as $server) {
    /** @var $server Rackspace\Compute\v2\Models\Server */
}