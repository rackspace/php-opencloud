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

/**
 * Pre-requisites:
 *
 * Prior to running this script, you must setup the following environment variables:
 * - RAX_USERNAME: Your Rackspace Cloud Account Username,
 * - RAX_API_KEY:  Your Rackspace Cloud Account API Key,
 * - RAX_REGION:   Rackspace Cloud region in which to create server; e.g.: DFW,
 * - RAX_BOOTABLE_VOLUME_ID: ID of bootable volume in Rackspace Cloud region, and
 * - RAX_FLAVOR_ID: ID of a compute flavor (performance or higher) in the Rackspace Cloud; e.g.: performance1-1.
 *
 * - You have an existing keypair. For this script, it will be called 'my_keypair'
 * but this will change depending on what you called yours.
 */

require __DIR__ . '/../../vendor/autoload.php';

use OpenCloud\Rackspace;
use Guzzle\Http\Exception\BadResponseException;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('RAX_USERNAME'),
    'apiKey'   => getenv('RAX_API_KEY')
));

// 2. Create Compute service
$region = getenv('RAX_REGION');
$computeService = $client->computeService(null, $region);
$volumeService = $client->volumeService(null, $region);

// 3. Get empty server
$server = $computeService->server();

// 4. Select bootable volume
$bootableVolume = $volumeService->volume(getenv('RAX_BOOTABLE_VOLUME_ID'));

// 5. Select a hardware flavor
$flavor = $computeService->flavor(getenv('RAX_FLAVOR_ID'));

// 6. Create
try {
    $response = $server->create(array(
        'name'     => 'My server created from a bootable volume',
        'volume'   => $bootableVolume,
        'flavor'   => $flavor
    ));
} catch (BadResponseException $e) {
    // No! Something failed. Let's find out:
    echo $e->getRequest() . PHP_EOL . PHP_EOL;
    echo $e->getResponse();
}
