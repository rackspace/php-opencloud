<?php

require 'vendor/autoload.php';

$openstack = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->computeV2(['region' => '{region}']);

$volumeAttachment = $service->getVolumeAttachment('{id}');

$volumeAttachment->mergeMetadata(['key' => 'value']);