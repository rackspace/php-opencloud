<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV1(['region' => '{region}']);

foreach ($service->listResource('{id}') as $resource) {
    /** @var $resource Rackspace\Network\v1\Models\Resource */
}