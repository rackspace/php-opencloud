<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->objectStoreV1();

foreach ($service->listContainers() as $container) {
    /** @var $container \OpenStack\ObjectStore\v1\Models\Container */
}
