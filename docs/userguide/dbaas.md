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
$database = $service->instance();

// Send to the API
$database->create(array(
    // Pass in a particular flavor object
    'flavor' => $service->flavor('<flavor_id>'),
    // Specify a 4GB volume
    'volume' => array('size' => 4)
));
```

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