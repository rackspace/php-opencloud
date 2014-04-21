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
//   * RAX_API_KEY:  Your Rackspace Cloud Account API Key
// * There exists a container named 'logos' in your Object Store. Run
//   create-container.php if you need to create one first.
// * The 'logos' container contains two objects named 'php-elephant.jpg' and
//   'python-snakes.jpg'. Run upload-multiple-objects.php if you need to create
//    them first.
//

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\Rackspace;
use OpenCloud\ObjectStore\Constants\UrlType;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('RAX_USERNAME'),
    'apiKey'   => getenv('RAX_API_KEY')
));

// 2. Obtain an Object Store service object from the client.
$region = 'DFW';
$objectStoreService = $client->objectStoreService(null, $region);

// 3. Just to demonstrate bulk delete, create an empty container.
$objectStoreService->createContainer('some_empty_container');

// 4. Bulk delete objects and empty containers.
$response = $objectStoreService->bulkDelete(array(
    'logos/php-elephant.png',
    'logos/python-snakes.png',
    'some_empty_container'
));
