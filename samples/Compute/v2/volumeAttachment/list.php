<?php

require 'vendor/autoload.php';

$openstack = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

foreach ($service->listVolumeAttachment('{id}') as $volumeAttachment) {
    /** @var $volumeAttachment Rackspace\Compute\v2\Models\VolumeAttachment */
}