<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV2(['region' => '{region}']);

foreach ($service->listSubnet('{id}') as $subnet) {
    /** @var $subnet Rackspace\Network\v2\Models\Subnet */
}