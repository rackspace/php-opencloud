<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV1(['region' => '{region}']);

foreach ($service->listStack('{id}') as $stack) {
    /** @var $stack Rackspace\Network\v1\Models\Stack */
}