<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->rackConnectV3(['region' => '{region}']);

foreach ($service->listPublicIp('{id}') as $publicIp) {
    /** @var $publicIp Rackspace\RackConnect\v3\Models\PublicIp */
}