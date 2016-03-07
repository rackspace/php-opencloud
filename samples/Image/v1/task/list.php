<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->imageV1(['region' => '{region}']);

foreach ($service->listTask('{id}') as $task) {
    /** @var $task Rackspace\Image\v1\Models\Task */
}