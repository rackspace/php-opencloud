<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

/** @var \Rackspace\ObjectStoreCDN\v1\Models\Object $object */
$object = $rackspace->objectStoreCdnV1()
                    ->getContainer('{containerName}')
                    ->getObject('{objectName}');