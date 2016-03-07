<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV1(['region' => '{region}']);

$stack = $service->getStack('{id}');
$stack->delete();