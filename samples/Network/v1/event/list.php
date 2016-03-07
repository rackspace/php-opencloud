<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV1(['region' => '{region}']);

foreach ($service->listEvent('{id}') as $event) {
    /** @var $event Rackspace\Network\v1\Models\Event */
}