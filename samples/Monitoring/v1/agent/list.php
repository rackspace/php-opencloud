<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->monitoringV1(['region' => '{region}']);

foreach ($service->listAgent('{id}') as $agent) {
    /** @var $agent Rackspace\Monitoring\v1\Models\Agent */
}