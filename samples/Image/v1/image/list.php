<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->imageV1(['region' => '{region}']);

foreach ($service->listImage('{id}') as $image) {
    /** @var $image Rackspace\Image\v1\Models\Image */
}