<?php

require 'vendor/autoload.php';

use GuzzleHttp\Psr7\Stream;

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

// You can use any instance of \Psr\Http\Message\StreamInterface
$stream = new Stream(fopen('/path/to/object.txt', 'r'));

$options = [
    'name'   => '{objectName}',
    'stream' => $stream,
];

/** @var \OpenStack\ObjectStore\v1\Models\Object $object */
$object = $rackspace->objectStoreV1()
    ->getContainer('{containerName}')
    ->createObject($options);
