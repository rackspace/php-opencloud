<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->loadBalancerV1(['region' => '{region}']);

$sslTerminationConfig = $service->getSslTerminationConfig('{id}');
$sslTerminationConfig->delete();