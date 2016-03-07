<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->loadBalancerV1(['region' => '{region}']);

foreach ($service->listLoadBalancer('{id}') as $loadBalancer) {
    /** @var $loadBalancer Rackspace\LoadBalancer\v1\Models\LoadBalancer */
}