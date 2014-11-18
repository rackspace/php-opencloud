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

// You can replace {authUrl} with Rackspace::US_IDENTITY_ENDPOINT or similar
$client = new Rackspace('{authUrl}', array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
));

// Set up Identity service
$service = $client->identityService();

// Create user
$user = $service->createUser(array(
  'username' => '{username}', // replace username
  'email'    => '{email}',    // replace email address
  'enabled'  => true,
));

// If you do not provide a "password" key in the createUser operation, the API
// will automatically generate you one. If that's the case, you will need to
// retrieve the new password and save it somewhere.
echo $user->getPassword();
