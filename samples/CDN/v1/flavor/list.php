<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->cDNV1(['region' => '{region}']);

foreach ($service->listFlavor('{id}') as $flavor) {
    /** @var $flavor Rackspace\CDN\v1\Models\Flavor */
}