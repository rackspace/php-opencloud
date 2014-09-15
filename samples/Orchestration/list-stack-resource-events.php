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
//   * OS_USERNAME: Your OpenStack Cloud Account Username,
//   * NOVA_API_KEY:  Your OpenStack Cloud Account API Key,
//   * OS_REGION: The OpenStack Cloud region you want to use,
//   * STACK_NAME:   Name of stack, and
//   * STACK_RESOURCE_NAME: Name of resource in stack
//

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\OpenStack;

// 1. Instantiate a Rackspace client.
$client = new OpenStack(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('NOVA_API_KEY')
));

// 2. Obtain an Orchestration service object from the client.
$region = getenv('OS_REGION');
$orchestrationService = $client->orchestrationService(null, $region);

// 3. Get stack.
$stack = $orchestrationService->getStack(getenv('STACK_NAME'));

// 4. Get resource in stack.
$stackResource = $stack->getResource(getenv('RESOURCE_NAME'));

// 5. Get list of events for the stack resource.
$stackResourceEvents = $stackResource->listEvents();
foreach ($stackResourceEvents as $stackResourceEvent) {
    /** @var $stackResourceEvent OpenCloud\Orchestration\Resource\StackResourceEvent **/
}
