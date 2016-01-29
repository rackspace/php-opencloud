<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$container = $rackspace->objectStoreV1()
                       ->getContainer('{containerName}');

foreach ($container->listObjects() as $object) {
    /** @var \OpenStack\ObjectStore\v1\Models\Object $object */
}
