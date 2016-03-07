<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->dNSV1(['region' => '{region}']);

foreach ($service->listDomain('{id}') as $domain) {
    /** @var $domain Rackspace\DNS\v1\Models\Domain */
}