<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

/** @var array $metadata */
$metadata = $rackspace->objectStoreV1()
                      ->getContainer('{containerName}')
                      ->getMetadata();
