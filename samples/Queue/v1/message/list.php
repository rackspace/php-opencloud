<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->queueV1(['region' => '{region}']);

foreach ($service->listMessage('{id}') as $message) {
    /** @var $message Rackspace\Queue\v1\Models\Message */
}