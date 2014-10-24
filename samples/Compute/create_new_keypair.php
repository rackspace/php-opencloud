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

// Pre-requisites
// Prior to running this script, you must setup the following environment variables:
//   * RAX_USERNAME: Your Rackspace Cloud Account Username, and
//   * RAX_API_KEY:  Your Rackspace Cloud Account API Key

require dirname(__DIR__) . '/../../vendor/autoload.php';

use OpenCloud\Rackspace;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('RAX_USERNAME'),
    'apiKey'   => getenv('RAX_API_KEY')
));

// 2. Create Compute service object
$region = 'ORD';
$service = $client->computeService(null, $region);

// 3. Get empty keypair
$keypair = $service->keypair();

// 4. Create
$keypair->create(array(
    'name' => 'new_keypair'
));

echo $keypair->getPublicKey();

// 5. Save private key to a file so you can use it to SSH into your server later
$sshPrivateKeyFilename = 'new_keypair_private';
$privateKey = $keypair->getPrivateKey();
file_put_contents($sshPrivateKeyFilename, $privateKey);
chmod($sshPrivateKeyFilename, 0600);
