<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$options = [
    'name'    => '{objectName}',
    'content' => '{objectContent}',
];

/** @var \OpenStack\ObjectStore\v1\Models\Object $object */
$object = $rackspace->objectStoreV1()
                    ->getContainer('{containerName}')
                    ->createObject($options);
