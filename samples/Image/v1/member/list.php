<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->imageV1(['region' => '{region}']);

foreach ($service->listMember('{id}') as $member) {
    /** @var $member Rackspace\Image\v1\Models\Member */
}