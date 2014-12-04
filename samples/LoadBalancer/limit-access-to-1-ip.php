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

// Retrieve your load balancer
$lb = $service->loadBalancer('{loadBalancerId}');

// Limit access to just 1 IP CIDR
$lb->createAccessList(array(
    (object) array(
        'type'    => 'ALLOW',
        'address' => '{ipAddress}' // Add your own CIDR
    ),
    (object) array(
        'type'    => 'DENY',
        'address' => '0.0.0.0/0'
    )
));
