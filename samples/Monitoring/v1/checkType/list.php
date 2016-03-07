<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->monitoringV1(['region' => '{region}']);

foreach ($service->listCheckType('{id}') as $checkType) {
    /** @var $checkType Rackspace\Monitoring\v1\Models\CheckType */
}