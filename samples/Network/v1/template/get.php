<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV1(['region' => '{region}']);

$template = $service->getTemplate('{id}');

// By default, this will return an empty object and NOT hit the API.
// This is convenient for when you want to use the object for operations
// that do not require an initial GET request. To retrieve the resource's details,
// run the following, which *will* call the API with a GET request:

$template->retrieve();