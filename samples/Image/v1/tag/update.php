<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->imageV1(['region' => '{region}']);

$tag = $service->getTag('{id}');
$tag->delete();