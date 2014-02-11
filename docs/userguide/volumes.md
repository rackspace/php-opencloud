Working with Volumes
----------------------------------------------
Cloud Block Storage (CBS) is a dynamic volume creation and management service
built upon the OpenStack Cinder project. See http://cinder.openstack.org for
complete details about the available features and functionality.

## Setup

```php
$service = $client->volumeService('cloudBlockStorage', 'DFW');
```

For more information about setting up client objects, see the
[client documentation](Clients.md). For more information about service objects,
including a full list of expected arguments, see the
[service documentation](Services.md).

## Volume Types

Providers may support multiple types of volumes; at Rackspace, a volume can
either be `SSD` (solid state disk: expensive, high-performance) or
`SATA` (serial attached storage: regular disks, less expensive).

### List volume types

```php
$volumeTypes = $service->volumeTypeList();

foreach ($volumeTypes as $volumeType) {
    /** @param $volumeType OpenCloud\Volume\Resource\VolumeType */
}
```

For more information about working with iterators, please see the
[iterators documentation](Iterators.md).

### Describe a volume type

If you know the ID of a volume type, use the `volumeType` method to retrieve
information on it:

```php
$volumeType = $service->volumeType(1);
```

A volume type has three attributes:

* `$id` the volume type identifier
* `$name` its name
* `$extra_specs` additional information for the provider

## Volumes

A volume is a detachable block storage device. You can think of it as a USB
hard drive. It can only be attached to one instance at a time.

A volume is represented by the `OpenCloud\Volume\Resource\Volume` class.

### To create a volume

To create a volume, you must specify its size (in gigabytes). All other
parameters are optional:

```php
// Create instance of OpenCloud\Volume\Resource\Volume
$volume = $service->volume();

$volume->create(array(
    'size'                => 200,
    'volume_type'         => $service->volumeType('<volume_type_id>'),
    'display_name'        => 'My Volume',
    'display_description' => 'Used for large object storage'
));
```

### List volumes

```php
$volumes = $service->volumeList();

foreach ($volumes as $volume) {
    /** @param $volumeType OpenCloud\Volume\Resource\Volume */
}
```

For more information about working with iterators, please see the
[iterators documentation](Iterators.md).

### Get details on a single volume

If you specify an ID on the `volume()` method, it retrieves information on
the specified volume:

```php
$volume = $dallas->volume('<volume_id>');
echo $volume->size;
```

### To delete a volume

```php
$volume->delete();
```

## Snapshots

A snapshot is a point-in-time copy of the data contained in a volume. It is
represented by the `OpenCloud\Volume\Resource\Snapshot` class.

### Create a snapshot

A `Snapshot` object is created from the Cloud Block Storage service. However,
it is associated with a volume, and you must specify a volume to create one:

```php
// New instance of OpenCloud\Volume\Resource\Snapshot
$snapshot = $service->snapshot();

// Send to API
$snapshot->create(array(
    'display_name' => 'Name that snapshot',
    'volume_id'    => $volume->id
));
```

### List snapshots

```php
$snapshots = $service->snapshotList();

foreach ($snapshots as $snapshot) {
    /** @param $snapshot OpenCloud\Volume\Resource\Snapshot */
}
```

For more information about working with iterators, please see the
[iterators documentation](Iterators.md).

### To get details on a single snapshot

```php
$snapshot = $dallas->snapshot('<snapshot_id>');
```

#### To delete a snapshot

```php
$snapshot->delete();
```

### Volumes and Servers

A volume by itself is useful when it is attached to a server so that the
server can use the volume.

### To attach a volume to a server

```php
$server->attachVolume($volume, '<mount_point>')
```

The `<mount_point>` is the location on the server on which to
mount the volume (usually `/dev/xvhdd` or similar). You can also supply
`'auto'` as the mount point, in which case the mount point will be
automatically selected for you. `auto` is the default value for
`{mount-point}`, so you do not actually need to supply anything for that
parameter.

### To detach a volume from a server

```php
$server->detachVolume($volume);
```