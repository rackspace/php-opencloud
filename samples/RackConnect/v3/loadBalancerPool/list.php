<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->rackConnectV3(['region' => '{region}']);

foreach ($service->listLoadBalancerPool('{id}') as $loadBalancerPool) {
    /** @var $loadBalancerPool Rackspace\RackConnect\v3\Models\LoadBalancerPool */
}