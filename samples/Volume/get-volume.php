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

//
// Pre-requisites:
// * Prior to running this script, you must setup the following environment variables:
//   * RAX_USERNAME: Your Rackspace Cloud Account Username, and
//   * RAX_API_KEY:  Your Rackspace Cloud Account API Key, and
//   * RAX_VOLUME_ID: ID of the volume you want to get. Run create-volume.php if
//     you need to create one first.
//

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\Rackspace;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('RAX_USERNAME'),
    'apiKey'   => getenv('RAX_API_KEY')
));

// 2. Obtain a Volume service object from the client.
$region = 'DFW';
$volumeService = $client->volumeService(null, $region);

// 3. Get the volume.
$volume = $volumeService->volume(getenv('RAX_VOLUME_ID'));

/** @var $volume OpenCloud\Volume\Resource\Volume **/
echo "ID: " . $volume->id() . " | " . "Name: " . $volume->name() . PHP_EOL;
