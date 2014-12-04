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

// 4. Setup headers with custom content type.
// NOTE: Normally you do NOT need to specify the content type. The
// service will automatically guess it for you. If, however, you
// are using an obscure type of content or want to be explicit about
// the content type rather than letting the service guess at it,
// you may specify it as shown below.
$customHeaders = array('Content-Type' => '{contentType}');

// 5. Open local file
$fileData = fopen('{localFileName}', 'r');

// 6. Upload to API. Note that while we call fopen to open the file resource,
// we do not call fclose at the end. The file resource is automatically closed
// inside the uploadObject call.
$container->uploadObject('{remoteObjectName}', $fileData, $customHeaders);
