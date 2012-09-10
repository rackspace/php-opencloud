Factory methods
===============

Much of the work in <b>php-opencloud</b> is handled by _factory methods_.
These are special functions that return a specified object. For example,
to work with an instance of a service, use the factory method on the
top-level provider object:

    <?php
    require('openstack.inc');
    $cloud = new OpenStack('endpoint',array(...));
    $swift = $cloud->ObjectStore(...);

In this case, the variable `$swift` now holds an instance of the `ObjectStore`
class. Likewise, the `Compute()` method returns a `Compute` object:

    $nova = $cloud->Compute(...);

Each of _those_ objects has its own factory methods as well.

## Compute object factory methods

Each of these returns the corresponding object.

* `Compute::Server()`
* `Compute::Image()`
* `Compute::Flavor()`

## Compute collection factory methods

Each of these methods returns a [Collection](collections.md) of the
associated objects.

* `Compute::ServerList()`
* `Compute::ImageList()`
* `Compute::FlavorList()`

## Object storage factory methods

* `ObjectStore::Container()`
* `ObjectStore::ContainerList()`

## Object storage container factory methods

* `Container::Object()`
* `Container::ObjectList()`
