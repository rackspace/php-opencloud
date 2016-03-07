<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->monitoringV1(['region' => '{region}']);

foreach ($service->listNotification('{id}') as $notification) {
    /** @var $notification Rackspace\Monitoring\v1\Models\Notification */
}