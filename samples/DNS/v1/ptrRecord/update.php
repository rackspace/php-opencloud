<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->dNSV1(['region' => '{region}']);

$ptrRecord = $service->getPtrRecord('{id}');
$ptrRecord->delete();