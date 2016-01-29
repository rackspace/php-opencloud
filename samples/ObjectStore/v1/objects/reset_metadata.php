<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

$rackspace->objectStoreV1()
          ->getContainer('{containerName}')
          ->getObject('{objectName}')
          ->resetMetadata([
              '{key_1}' => '{val_1}',
              '{key_2}' => '{val_2}',
          ]);
