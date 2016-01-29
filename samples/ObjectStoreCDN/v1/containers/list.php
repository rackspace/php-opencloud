<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->objectStoreCdnV1();

foreach ($service->listContainers() as $container) {
    /** @param \Rackspace\ObjectStoreCDN\v1\Models\Container $container */
}