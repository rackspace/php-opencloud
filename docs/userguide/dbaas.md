Working with Cloud Databases
============================

Cloud Databases is a "database-as-a-service" product offered by Rackspace. Since it is
not an official OpenStack project, it is only available via Rackspace connections,
and *not* through an OpenStack connection.

## Setup

```php
$service = $client->databaseService('cloudDatabases', 'ORD');
```

For more information about setting up client objects, see the
[client documentation](Clients.md). For more information about service objects,
including a full list of expected arguments, see the
[service documentation](Services.md).

## Instances

A database instance is an isolated MySQL instance in a single tenant environment
on a shared physical host machine. Also referred to as instance.

### Create a new Instance

```php
// Create an empty OpenCloud\Database\Resource\Instance object
$instance = $service->instance();

// Send to the API
$instance->create(array(
    // Pass in a name for your database instance
    'name'   => '<instance_name>',
    // Pass in a particular flavor object
    'flavor' => $service->flavor('<flavor_id>'),
    // Specify a 4GB volume
    'volume' => array('size' => 4)
));
```
[ [Get the executable PHP script for this example](/samples/Database/create-instance.php) ]

### Retrieving an instance

```php
$instance = $service->instance('<instance ID>');
```
[ [Get the executable PHP script for this example](/samples/Database/get-instance.php) ]

### Updating an instance
An instance can be updated to use a specific [configuration](#configurations) as shown below.

```php
$instance->update(array(
    'configuration' => '<configuration ID>'
));
```

**Note:** If any parameters in the associated configuration require a restart, then
you will need to [restart the instance](#restarting-an-instance) after the update.

### Deleting an instance

```php
$instance->delete();
```

### Restarting an instance

```php
$instance->restart();
```

### Resizing an instance

There are two methods for resizing an instance. The first, `resize()`, changes the amount
of RAM allocated to the instance:

```php
$flavor = $service->flavor('<bigger_flavor_id>');
$instance->resize($flavor);
```

You can also independently change the volume size to increase the disk space:

```php
// Increase to 8GB disk
$instance->resizeVolume(8);
```

## Databases

Instances can have multiple databases; the `OpenCloud\Database\Resource\Database`
class corresponds to a single MySQL database.

### Creating a new database

To create a new database, you must supply it with a name; you can optionally 
specify its character set and collating sequence:

```php
// Create a new OpenCloud\Database\Resource\Database object
$database = $instance->database();

// Send to API
$database->create(array(
    'name'          => 'production',
    'character_set' => 'utf8',
    'collate'       => 'utf8_general_ci'
));
```

You can find values for `character_set` and `collate` at
[the MySQL website](http://dev.mysql.com/doc/refman/5.0/en/charset-mysql.html).

### Deleting a database

```php
$database->delete();
```

Note that this is destructive; all your data will be wiped away and will not be
retrievable.

### Listing databases

```php
$databases = $service->databaseList();

foreach ($databases as $database) {
    /** @param $database OpenCloud\Database\Resource\Database */
}
```

For more information about working with iterators, please see the
[iterators documentation](Iterators.md).

### Creating users

Database users exist at the `Instance` level, but can be associated with a specific
`Database`. They are represented by the `OpenCloud\Database\Resource\User` class.

Users cannot be altered after they are created, so they must be assigned to
databases when they are created:

```php
// New instance of OpenCloud\Database\Resource\User
$user = $instance->user();

// Send to API
$user->create(array(
    'name'      => 'Alice',
    'password'  => 'fooBar'
    'databases' => array('production')
));
```

If you need to add a new database to a user after it's been created, you must 
delete the user and then re-add it.

### Deleting users

```php
$user->delete();
```

## The root user

By default, Cloud Databases does not enable the root user. In most cases, the root
user is not needed, and having one can leave you open to security violations. However,
if you want to enable access to the root user, the `enableRootUser()` method is
available:

```php
$rootUser = $instance->enableRootUser();
```

This returns a regular `User` object with the `name` attribute set to `root` and the
`password` attribute set to an auto-generated password. 

To check if the root user is enabled, use the `isRootEnabled()` method.

## Configurations

Configurations are groups of settings that can be [applied to instances](#updating-an-instance).

### Creating a configuration
You can create a new configuration as shown below:

```php
$configuration = $service->configuration();
$configuration->create(array(
    'name'   => 'example-configuration-name',
    'description' => 'An example configuration',
    'values' => array(
        'collation_server' => 'latin1_swedish_ci',
        'connect_timeout' => 120
    ),
    'datastore' => array(
        'type' => '10000000-0000-0000-0000-000000000001',
        'version' => '1379cc8b-4bc5-4c4a-9e9d-7a9ad27c0866'
    )
));
/** @var $configuration OpenCloud\Database\Resource\Configuration **/
```
[ [Get the executable PHP script for this example](/samples/Database/create-configuration.php) ]

### Listing configurations
You can list out all the configurations you have created as shown below:

```php
$configurations = $service->configurationList();
foreach ($configurations as $configuration) {
    /** @var $configuration OpenCloud\Database\Resource\Configuration **/
}
```
[ [Get the executable PHP script for this example](/samples/Database/list-configurations.php) ]

### Retrieving a configuration
You can retrieve a specific configuration, using its ID, as shown below:

```php
$configuration = $service->configuration(getenv('OS_DB_CONFIGURATION_ID'));
/** @var OpenCloud\Database\Resource\Configuration **/
```
[ [Get the executable PHP script for this example](/samples/Database/get-configuration.php) ]

### Updating a configuration
You have two choices when updating a configuration:
* You could [patch a configuration](#patching-a-configuration) to change only some configuration parameters, _or_
* You could [entirely replace a configuration](#replacing-a-configuration) to replace all configuration parameters with new ones.

#### Patching a configuration
You can patch a configuration as shown below:

```php
$configuration->patch(array(
    'values' => array(
        'connect_timeout' => 30
    )
));
```
[ [Get the executable PHP script for this example](/samples/Database/patch-configuration.php) ]

#### Replacing a configuration
You can replace a configuration as shown below:

```php
$configuration->update(array(
    'values' => array(
        'collation_server' => 'utf8_general_ci',
        'connect_timeout' => 60
    )
));
```
[ [Get the executable PHP script for this example](/samples/Database/replace-configuration.php) ]

### Deleting a configuration
You can delete a configuration as shown below:

```php
$configuration->delete();
```
[ [Get the executable PHP script for this example](/samples/Database/delete-configuration.php) ]

**Note:** You cannot delete a configuration if it is in use by a running instance.

### Listing instances using a configuration
You can list all instances using a specific configuration, using its ID, as shown below:

```php
$instances = $configuration->instanceList();
foreach ($instances as $instance) {
    /** @var $instance OpenCloud\Database\Resource\Instance **/
}
```
[ [Get the executable PHP script for this example](/samples/Database/list-configuration-instances.php) ]

## Datastores

Datastores are technologies avaialable to persist data.

### Listing datastores
You can list out all the datastores available as shown below:

```php
$datastores = $service->datastoreList();
foreach ($datastores as $datastore) {
    /** @var $datastore OpenCloud\Database\Resource\Datastore **/
}
```
[ [Get the executable PHP script for this example](/samples/Database/list-datastores.php) ]

### Retrieving a datastore
You can retrieve a specific datastore's information, using its ID, as shown below:

```php
$datastore = $service->datastore('<datastore ID>');
/** @var OpenCloud\Database\Resource\Datastore **/
```
[ [Get the executable PHP script for this example](/samples/Database/get-datastore.php) ]

### Listing datastore versions
You can list out all the versions available for a specific datastore, as shown below:

```php
$versions = $datastore->versionList();
foreach ($versions as $version) {
    /** @var $version OpenCloud\Database\Resource\DatastoreVersion **/
}
```
[ [Get the executable PHP script for this example](/samples/Database/list-datastore-versions.php) ]

### Retrieving a datastore version
You a retrieve a specific datastore version, using its ID, as shown below:

```php
$datastoreVersion = $datastore->version('<datastore version ID>');
/** @var OpenCloud\Database\Resource\DatastoreVersion **/
[ [Get the executable PHP script for this example](/samples/Database/get-datastore-version.php) ]
