<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

/** @var bool $exists */
$exists = $rackspace->objectStoreV1()
                    ->getContainer('{containerName}')
                    ->objectExists('{objectName}');
