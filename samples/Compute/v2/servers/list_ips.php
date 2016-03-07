<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

/** @var \Rackspace\Compute\v2\Models\Server $server */
$server = $service->getServer('{id}');

foreach ($server->getIpAddresses() as $ipAddress) {
    $privateIps = $ipAddress['private'];
    $publicIps = $ipAddress['public'];
    // echo $publicIps[0]->version, $publicIps[0]->addr;
}