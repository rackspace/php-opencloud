<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->databaseV1(['region' => '{region}']);

foreach ($service->listUser('{id}') as $user) {
    /** @var $user Rackspace\Database\v1\Models\User */
}