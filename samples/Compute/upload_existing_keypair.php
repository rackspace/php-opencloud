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

// 1. Instantiate a Rackspace client. You can replace {authUrl} with
// Rackspace::US_IDENTITY_ENDPOINT or similar
$client = new Rackspace('{authUrl}', array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
));

// 2. Create Compute service object
$service = $client->computeService(null, '{region}');

// 3. Get empty keypair
$keypair = $service->keypair();

$payload = <<<EOT
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDx8nkQv/zgGgB4rMYmIf+6A4l6Rr+o/6lHBQdW5aYd44bd8JttDCE/F/pNRr0lRE+PiqSPO8nDPHw0010JeMH9gYgnnFlyY3/OcJ02RhIPyyxYpv9FhY+2YiUkpwFOcLImyrxEsYXpD/0d3ac30bNH6Sw9JD9UZHYcpSxsIbECHw== Example public key
EOT;

// 4. Create
$keypair->create(array(
    'name'      => 'new_public_key',
    'publicKey' => $payload
));
