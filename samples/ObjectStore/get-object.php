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

// 2. Obtain an Object Store service object from the client.
$objectStoreService = $client->objectStoreService(null, '{region}');

// 3. Get container.
$container = $objectStoreService->getContainer('{containerName}');

// 4. Get object.

/** @var $object OpenCloud\ObjectStore\Resource\DataObject **/
$object = $container->getObject('{objectName}');

/** @var $objectContent Guzzle\Http\EntityBody **/
$objectContent = $object->getContent();

// 5. Write object content to file on local filesystem.
$objectContent->rewind();
$stream = $objectContent->getStream();
$localFilename = tempnam("/tmp", 'php-opencloud-');
file_put_contents($localFilename, $stream);
