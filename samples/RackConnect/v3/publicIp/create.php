<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->rackConnectV3(['region' => '{region}']);

$publicIp = $service->createPublicIp([

]);