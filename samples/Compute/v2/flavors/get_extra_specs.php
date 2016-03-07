<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

$flavor = $service->getFlavor('{id}');

/** @var array $specs */
$specs = $flavor->retrieveExtraSpecs();