<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV2(['region' => '{region}']);

foreach ($service->listNetwork('{id}') as $network) {
    /** @var $network Rackspace\Network\v2\Models\Network */
}