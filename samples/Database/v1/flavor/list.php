<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->databaseV1(['region' => '{region}']);

foreach ($service->listFlavor('{id}') as $flavor) {
    /** @var $flavor Rackspace\Database\v1\Models\Flavor */
}