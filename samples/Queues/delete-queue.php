<?php
/*
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
use OpenCloud\Common\Constants\Datetime;

$client = new Rackspace('{authUrl}', array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
));

$service = $client->queuesService(null, '{region}');

// You MUST set a client ID before executing any operation. This ID must be a
// valid UUID. The SDK can set a random UUID for you if you don't want to
// define your own, just leave the argument empty.
$service->setClientId();

// Get an existing queue
$queue = $service->getQueue('{queueName}');

// Permanently delete it
$queue->delete();
