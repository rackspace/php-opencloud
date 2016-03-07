<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->loadBalancerV1(['region' => '{region}']);

foreach ($service->listNode('{id}') as $node) {
    /** @var $node Rackspace\LoadBalancer\v1\Models\Node */
}