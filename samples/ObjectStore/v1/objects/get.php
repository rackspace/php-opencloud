<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

/** @var \OpenStack\ObjectStore\v1\Models\Object $object */
$object = $rackspace->objectStoreV1()
                    ->getContainer('{containerName}')
                    ->getObject('{objectName}');
