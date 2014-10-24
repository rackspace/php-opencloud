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
 * - RAX_USERNAME: Your Rackspace Cloud Account Username, and
 * - RAX_API_KEY:  Your Rackspace Cloud Account API Key
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
$region = 'ORD';
$service = $client->computeService(null, $region);

// 3. Get empty server
$server = $service->server();

// 4. Select an OS image
$images = $service->imageList();
foreach ($images as $image) {
    if (strpos($image->name, 'Ubuntu') !== false) {
        $ubuntuImage = $image;
        break;
    }
}

// 5. Select a hardware flavor
$flavors = $service->flavorList();
foreach ($flavors as $flavor) {
    if (strpos($flavor->name, '2GB') !== false) {
        $twoGbFlavor = $flavor;
        break;
    }
}

// 6. Create
try {
    $response = $server->create(array(
        'name'     => 'My lovely server',
        'image'    => $ubuntuImage,
        'flavor'   => $twoGbFlavor
    ));
} catch (BadResponseException $e) {
    // No! Something failed. Let's find out:
    $badResponse = $e->getResponse();
    echo sprintf(
        'Status: %s\nBody: %s\nHeaders: %s',
        $badResponse->getStatusCode(),
        $badResponse->getBody(true),
        implode(', ', $response->getHeaderLines())
    );
}

$body = json_decode($response->getBody(true));

// THIS IS YOUR ROOT PASSWORD - DO NOT LOSE!
$password = $body->server->adminPass;
