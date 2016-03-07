<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->loadBalancerV1(['region' => '{region}']);

foreach ($service->listVirtualIp('{id}') as $virtualIp) {
    /** @var $virtualIp Rackspace\LoadBalancer\v1\Models\VirtualIp */
}