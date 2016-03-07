<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->dNSV1(['region' => '{region}']);

foreach ($service->listSubdomain('{id}') as $subdomain) {
    /** @var $subdomain Rackspace\DNS\v1\Models\Subdomain */
}