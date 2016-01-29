<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$rackspace->objectStoreCdnV1()
    ->getContainer('{containerName}')
    ->enableCdnLogging();