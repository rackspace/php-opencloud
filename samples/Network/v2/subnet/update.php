<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV2(['region' => '{region}']);

$subnet = $service->getSubnet('{id}');
$subnet->delete();