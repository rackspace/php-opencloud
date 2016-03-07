<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->dNSV1(['region' => '{region}']);

$domain = $service->getDomain('{id}');
$domain->delete();