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

// Prior to running this script, you must setup the following environment variables:
//  * RS_AUTH_URL: your Rackspace authentication URL
//  * RS_USERNAME: your Rackspace username
//  * RS_API_KEY: your Rackspace API key

require dirname(__DIR__) . '/../vendor/autoload.php';

use OpenCloud\Rackspace;
use OpenCloud\Identity\Constants\User as UserConst;

$client = new Rackspace(getenv('RS_AUTH_URL'), array(
    'username' => getenv('RS_USERNAME'),
    'apiKey'   => getenv('RS_API_KEY'),
));

// Set up Identity service
$service = $client->identityService();

// Retrieve existing user
$user = $service->getUser('{username}');

// And delete them...
$user->delete();
