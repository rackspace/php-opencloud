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

// Get your queue
$queue = $service->getQueue('{queueName}');

// Post a new message. The body attribute specifies an arbitrary document that
// constitutes the body of the message being sent. The size of this body is
// limited to 256 KB, excluding whitespace. The ttl attribute specifies how
// long the server waits before marking the message as expired and removing it
// from the queue. The value of ttl must be between 60 and 1209600 seconds
$queue->createMessage(array(
    'body' => (object) array( // This can be anything you want
      'event' => 'foo'        // and not necessarily an object
    ),
    'ttl' => 2 * Datetime::DAY,
));
