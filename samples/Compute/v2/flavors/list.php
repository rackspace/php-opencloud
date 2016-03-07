<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

foreach ($service->listFlavors('{id}') as $flavor) {
    /** @var $flavor Rackspace\Compute\v2\Models\Flavor */
}