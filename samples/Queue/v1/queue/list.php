<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->queueV1(['region' => '{region}']);

foreach ($service->listQueue('{id}') as $queue) {
    /** @var $queue Rackspace\Queue\v1\Models\Queue */
}