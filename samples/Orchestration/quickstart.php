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
//   * OS_USERNAME: Your Rackspace Cloud Account Username,
//   * OS_PASSWORD:  Your Rackspace Cloud Account Password, and
//   * OS_REGION_NAME: The Rackspace Cloud region you want to use
//

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\Rackspace;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'password'   => getenv('OS_PASSWORD')
));

// 2. Obtain an Orchestration service object from the client.
$region = getenv('OS_REGION_NAME');
$orchestrationService = $client->orchestrationService(null, $region);

// 3. Create a stack.
$stack = $orchestrationService->stack();
$stack->create(array(
    'name'         => 'Cloud server with attached block storage',
    'templateUrl'  => 'https://raw.githubusercontent.com/openstack/heat-templates/master/hot/vm_with_cinder.yaml',
    'parameters'   => array(
        'key_name' => 'mine',
        'flavor'   => 'performance1_1',
        'image'    => '0112b238-4267-4a22-9785-fcf75814bc2f' // Ubuntu 14.04 LTS (Trusty Tahr)
    ),
    'timeoutMins'  => 5
));
