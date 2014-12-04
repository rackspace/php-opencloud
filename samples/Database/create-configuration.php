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

// 1. Instantiate a Rackspace client. You can replace {authUrl} with
// Rackspace::US_IDENTITY_ENDPOINT or similar
$client = new Rackspace('{authUrl}', array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
));

// 2. Obtain a Database service object from the client.
$databaseService = $client->databaseService(null, '{region}');

// 3. Create a configuration.
$configuration = $databaseService->configuration();
$configuration->create(array(
    'name'        => 'example-configuration-name',
    'description' => 'An example configuration',
    'values'      => array(
        'collation_server' => 'latin1_swedish_ci',
        'connect_timeout'  => 120
    ),
    'datastore' => array(
        'type'    => '10000000-0000-0000-0000-000000000001',
        'version' => '1379cc8b-4bc5-4c4a-9e9d-7a9ad27c0866'
    )
));
/** @var $configuration OpenCloud\Database\Resource\Configuration **/
