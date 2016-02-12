<?php

require 'vendor/autoload.php';

$openstack = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->databaseV1(['region' => '{region}']);

foreach ($service->listInstance('{id}') as $instance) {
    /** @var $instance Rackspace\Database\v1\Models\Instance */
}