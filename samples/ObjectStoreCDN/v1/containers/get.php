<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

/** @var \Rackspace\ObjectStoreCDN\v1\Models\Container $container */
$container = $rackspace->objectStoreCdnV1()
                       ->getContainer('{containerName}');