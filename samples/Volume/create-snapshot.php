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

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\Rackspace;

// 1. Instantiate a Rackspace client.
$client = new Rackspace('{authUrl}', array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
));

// 2. Obtain a Snapshot service object from the client.
$volumeService = $client->volumeService(null, '{region}');

// 3. Get the volume you want to snapshot.
$volume = $volumeService->volume('{volumeId}');

// 4. Create the snapshot. Specify a name and the volume ID.
$snapshot = $volumeService->snapshot();
$snapshot->create(array(
    'display_name' => 'backup20140514',
    'display_description' => 'Daily backup taken on May 14, 2014',
    'volume_id' => $volume->id()
));
