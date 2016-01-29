<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

/** @var \GuzzleHttp\Stream\Stream $stream */
$stream = $rackspace->objectStoreV1()
                    ->getContainer('{containerName}')
                    ->getObject('{objectName}')
                    ->download();
