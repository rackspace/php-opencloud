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

// 1. Instantiate an OpenStack client.
$client = new OpenStack('{authUrl}', array(
    'username' => '{username}',
    'password' => '{password}',
));

// 2. Obtain an Orchestration service object from the client.
$orchestrationService = $client->orchestrationService(null, '{region}');

// 3. Get stack.
$stack = $orchestrationService->getStack('{stackName}');

// 4. Update stack.
$stack->update(array(
    'template'      => '{yamlTemplateFilePath}',
    'parameters'    => array(
        'server_hostname' => '{serverHost}',
        'image'           => '{image}'
    ),
    'timeoutMins'   => 5
));
