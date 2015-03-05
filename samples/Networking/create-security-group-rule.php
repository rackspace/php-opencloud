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

// 2. Obtain a Networking service object from the client.
$networkingService = $client->networkingService(null, '{region}');

// 3. Create a security group rule.
$securityGroupRule = $networkingService->createSecurityGroupRule(array(
    'securityGroupId' => '2076db17-a522-4506-91de-c6dd8e837028',
    'direction'       => 'egress',
    'ethertype'       => 'IPv4',
    'portRangeMin'    => 80,
    'portRangeMax'    => 80,
    'protocol'        => 'tcp',
    'remoteGroupId'   => '85cc3048-abc3-43cc-89b3-377341426ac5'
));
/** @var $securityGroupRule OpenCloud\Networking\Resource\SecurityGroupRule **/
