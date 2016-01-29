<?php

require 'vendor/autoload.php';

$rackspace = new Rackspace\Rackspace([
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
]);

/** @var bool $result */
$result = $rackspace->objectStoreCdnV1()
                    ->getContainer('{containerName}')
                    ->isCdnLoggingEnabled();