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
 *
 * - There exists a server named 'my_server' and you know its ID. Run
 * create_server.php if you need to create one first.
 */

require __DIR__ . '/../../vendor/autoload.php';

use OpenCloud\Rackspace;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('RAX_USERNAME'),
    'apiKey'   => getenv('RAX_API_KEY')
));

// 2. Create Compute service object
$region = 'ORD';
$service  = $client->computeService(null, $region);

// 3. Get your existing server
$serverId = '7af22b0d-7d01-4460-a222-77e65a9ab184';
$server = $service->server($serverId);

// 4. Delete it
$server->delete();
