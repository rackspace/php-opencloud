<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->monitoringV1(['region' => '{region}']);

foreach ($service->listEntity('{id}') as $entity) {
    /** @var $entity Rackspace\Monitoring\v1\Models\Entity */
}