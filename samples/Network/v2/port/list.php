<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV2(['region' => '{region}']);

foreach ($service->listPort('{id}') as $port) {
    /** @var $port Rackspace\Network\v2\Models\Port */
}