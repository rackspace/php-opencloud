<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->monitoringV1(['region' => '{region}']);

foreach ($service->listZone('{id}') as $zone) {
    /** @var $zone Rackspace\Monitoring\v1\Models\Zone */
}