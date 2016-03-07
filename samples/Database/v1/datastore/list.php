<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->databaseV1(['region' => '{region}']);

foreach ($service->listDatastore('{id}') as $datastore) {
    /** @var $datastore Rackspace\Database\v1\Models\Datastore */
}