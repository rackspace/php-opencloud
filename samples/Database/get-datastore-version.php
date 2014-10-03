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
//   * RAX_API_KEY:  Your Rackspace Cloud Account API Key,
//   * OS_REGION_NAME: OpenStack Cloud region in which to create database instance,
//   * OS_DB_DATASTORE_ID: ID of database datastore, and
//   * OS_DB_DATASTORE_VERSION_ID: Id of database datastore version
//

require __DIR__ . '/../../vendor/autoload.php';
use OpenCloud\Rackspace;

// 1. Instantiate a Rackspace client.
$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('RAX_API_KEY')
));

// 2. Obtain a Databae service object from the client.
$region = getenv('OS_REGION_NAME');
$databaseService = $client->databaseService(null, $region);

// 3. Retrieve the database datastore.
$datastore = $databaseService->datastore(getenv('OS_DB_DATASTORE_ID'));

// 4. Retrieve the database datastore version.
$datastoreVersion = $datastore->version(getenv('OS_DB_DATASTORE_VERSION_ID'));
/** @var OpenCloud\Database\Resource\DatastoreVersion **/
