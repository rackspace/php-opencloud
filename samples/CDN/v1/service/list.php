<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->cDNV1(['region' => '{region}']);

foreach ($service->listService('{id}') as $service) {
    /** @var $service Rackspace\CDN\v1\Models\Service */
}