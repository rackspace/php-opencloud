<?php

/*
 * Copyright 2014 Rackspace US, Inc.
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
use OpenCloud\Compute\Constants\ServerState;
use Guzzle\Http\Exception\BadResponseException;

// 1. Instantiate a Rackspace client. You can replace {authUrl} with
// Rackspace::US_IDENTITY_ENDPOINT or similar
$client = new Rackspace('{authUrl}', array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
));

// 2. Create Compute service
$service = $client->computeService(null, '{region}');

// 3. Get empty server
$server = $service->server();

// 4. Create the server. If you do not know what imageId or flavorId to use,
// please run the list_flavors.php and list_images.php scripts.
try {
    $response = $server->create(array(
        'name'     => '{serverName}',
        'imageId'  => '{imageId}',
        'flavorId' => '{flavorId}'
    ));
} catch (BadResponseException $e) {
    echo $e->getResponse();
}

// 5. Wait for the server to become ACTIVE.
$server->waitFor(ServerState::ACTIVE);

// 6. Retrieve the server's IP
$ipv4Address = $server->accessIPv4;
