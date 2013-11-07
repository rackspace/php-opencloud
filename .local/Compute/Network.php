<?php

require __DIR__ . '/../../lib/php-opencloud.php';

use OpenCloud\Rackspace;

$client = new Rackspace(RACKSPACE_US, array(
    'username' => 'jamiehannaford1',
    'apiKey'   => '504de62cad6b2356d378027e40d25d7c'
));

$service = $client->computeService('cloudServersOpenStack');

$network = $service->network('f6d54faf-ed32-4706-a8a4-84f6f0722957');

//$network->create(array(
//   'cidr' => '192.168.0.1/24',
//   'label' => 'blah'
//));

var_dump($network);