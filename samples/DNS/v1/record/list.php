<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->dNSV1(['region' => '{region}']);

foreach ($service->listRecord('{id}') as $record) {
    /** @var $record Rackspace\DNS\v1\Models\Record */
}