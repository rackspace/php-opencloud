<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->cDNV1(['region' => '{region}']);

$sslCertificate = $service->getSslCertificate('{id}');
$sslCertificate->delete();