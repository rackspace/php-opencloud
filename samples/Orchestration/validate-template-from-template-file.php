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

use OpenCloud\OpenStack;
use OpenCloud\Common\Exceptions\InvalidTemplateError;

// 1. Instantiate an OpenStack client.
$client = new OpenStack('{authUrl}', array(
    'username' => '{username}',
    'password' => '{password}',
));

// 2. Obtain an Orchestration service object from the client.
$orchestrationService = $client->orchestrationService(null, '{region}');

// 3. Validate template from file.
try {
    $orchestrationService->validateTemplate(array(
        'template' => '{yamlTemplateFilePath}',
    ));
} catch (InvalidTemplateError $e) {
    // Use $e->getMessage() for explanation of why template is invalid
}
