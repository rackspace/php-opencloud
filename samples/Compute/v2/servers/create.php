<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

$server = $service->createServer([
    "name"        => "api-test-server-1",
    "imageId"     => "{imageId}",
    "flavorId"    => "{flavorId}",
    "configDrive" => true,
    "diskConfig"  => "AUTO",
    "metadata"    => [
        "My Server Name" => "API Test Server 1",
    ],
]);