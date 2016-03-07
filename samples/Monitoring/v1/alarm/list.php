<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->monitoringV1(['region' => '{region}']);

foreach ($service->listAlarm('{id}') as $alarm) {
    /** @var $alarm Rackspace\Monitoring\v1\Models\Alarm */
}