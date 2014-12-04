<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require dirname(__DIR__) . '/../vendor/autoload.php';

use OpenCloud\Rackspace;

$client = new Rackspace('{authUrl}', array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
));

$service = $client->loadBalancerService(null, '{region}');

// Create empty object
$lb = $service->loadBalancer();

// Optional: add back-end nodes that need to be load balanced. These are
// usually servers.
$serverNode = $lb->node(array(
    'address'   => '{ipAddress}', // substitute your server's IPv4 address
    'port'      => 80,
    'condition' => 'ENABLED',
));

// Configure the type of Virtual IP your LB will use
$lb->addVirtualIp('PUBLIC');

// Create it
$lb->create(array(
    'name'     => '{name}',
    'port'     => 80,
    'protocol' => 'HTTP',
    'nodes'    => array($serverNode)
));
