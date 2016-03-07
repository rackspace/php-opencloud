<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV2(['region' => '{region}']);

foreach ($service->listSecurityGroup('{id}') as $securityGroup) {
    /** @var $securityGroup Rackspace\Network\v2\Models\SecurityGroup */
}