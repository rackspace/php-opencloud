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
//   * RAX_USERNAME: Your Rackspace Cloud Account Username,
//   * RAX_API_KEY:  Your Rackspace Cloud Account API Key,
//   * NUM_OBJECTS:  Number of objects (aka files) to return, and
//   * OBJECT_PREFIX: Initial part of object (aka file) name
// * There exists a container named 'logos' in your Object Store. Run
//   create-container.php if you need to create one first.
//

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\Rackspace;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('RAX_USERNAME'),
    'apiKey'   => getenv('RAX_API_KEY')
));

// 2. Obtain an Object Store service object from the client.
$region = 'DFW';
$objectStoreService = $client->objectStoreService(null, $region);

// 3. Get container.
$container = $objectStoreService->getContainer('logos');

// 4. Get list of objects whose names start with "php" in container.
$options = array(
    'limit'  => getenv('NUM_OBJECTS'),
    'prefix' => getenv('OBJECT_PREFIX')
);
$objects = $container->objectList($options);
foreach ($objects as $object) {
    /** @var $object OpenCloud\ObjectStore\Resource\DataObject  **/
    printf("Object name: %s\n", $object->getName());
}
