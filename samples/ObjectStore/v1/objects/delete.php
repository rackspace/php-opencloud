<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$rackspace->objectStoreV1()
          ->getContainer('{containerName}')
          ->getObject('{objectName}')
          ->delete();
