<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->monitoringV1(['region' => '{region}']);

foreach ($service->listNotificationPlan('{id}') as $notificationPlan) {
    /** @var $notificationPlan Rackspace\Monitoring\v1\Models\NotificationPlan */
}