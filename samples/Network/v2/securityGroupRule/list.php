<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$service = $rackspace->networkV2(['region' => '{region}']);

foreach ($service->listSecurityGroupRule('{id}') as $securityGroupRule) {
    /** @var $securityGroupRule Rackspace\Network\v2\Models\SecurityGroupRule */
}