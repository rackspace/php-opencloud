<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->databaseV1(['region' => '{region}']);

foreach ($service->listScheduledBackup('{id}') as $scheduledBackup) {
    /** @var $scheduledBackup Rackspace\Database\v1\Models\ScheduledBackup */
}