<?php

require 'vendor/autoload.php';

$openstack = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

foreach ($service->listVirtualInterface('{id}') as $virtualInterface) {
    /** @var $virtualInterface Rackspace\Compute\v2\Models\VirtualInterface */
}