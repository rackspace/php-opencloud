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
//   * OS_AUTH_URL: Your Rackspace Cloud Authentication URL,
//   * OS_USERNAME: Your Rackspace Cloud Account Username,
//   * RAX_API_KEY:  Your Rackspace Cloud Account API KEY, and
//   * OS_REGION_NAME: The Rackspace Cloud region you want to use
//

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\Rackspace;

// 1. Instantiate an Rackspace client.
$client = new Rackspace(getenv('OS_AUTH_URL'), array(
    'username' => getenv('OS_USERNAME'),
    'apiKey' => getenv('RAX_API_KEY')
));

// 2. Obtain an LoadBalancer service object from the client.
$region = getenv('OS_REGION_NAME');
$loadBalancerService = $client->loadBalancerService(null, $region);

// 3. Get load balancers.
$loadBalancers = $loadBalancerService->loadBalancerList();
foreach ($loadBalancers as $loadBalancer) {
    /** @var $loadBalancer OpenCloud\LoadBalancer\Resource\LoadBalancer **/
    var_dump($loadBalancer);
}
