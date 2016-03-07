<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->dNSV1(['region' => '{region}']);

foreach ($service->listPtrRecord('{id}') as $ptrRecord) {
    /** @var $ptrRecord Rackspace\DNS\v1\Models\PtrRecord */
}