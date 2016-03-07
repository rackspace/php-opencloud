<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->databaseV1(['region' => '{region}']);

foreach ($service->listDatastoreVersion('{id}') as $datastoreVersion) {
    /** @var $datastoreVersion Rackspace\Database\v1\Models\DatastoreVersion */
}