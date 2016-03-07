<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->databaseV1(['region' => '{region}']);

foreach ($service->listBackup('{id}') as $backup) {
    /** @var $backup Rackspace\Database\v1\Models\Backup */
}