Working with Flavors
====================

A *flavor* is a named definition of certain server parameters such as the
amount of RAM and disk space available. (There are other parameters set via
the flavor, such as the amount of disk space and the number of virtual CPUs,
but a discussion of those is too in-depth for a simple Getting Started Guide
like this one.)

## Get a flavor

A `Flavor` object is generated from the `flavor()` method on an
`OpenCloud\Compute\Service` object:

```php
$flavor = $service->flavor('<flavor_id>');
```

## List flavors

```php
$flavors = $service->flavorList();

foreach ($flavors as $flavor) {
    /** @param $flavor OpenCloud\Common\Resource\FlavorInterface */
}
```

For more information about working with iterators, please see the
[iterators documentation](Iterators.md).

### Details

By default, the `flavorList()` method returns full details on all flavors.
However, because of the overhead involved in retrieving all the details,
this function can be slower than expected. You can supply an optional
boolean parameter to the `flavorList()` method to determine whether or not
the flavor details are included:

```php
// Name and ID only
$compute->flavorList(false);

// All details
$compute->flavorList(true);
```

### Filters

The optional second parameter to the `flavorList()` method is an
associative array of filter parameters. See the 
[List Flavors operation](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/List_Flavors-d1e4188.html)
 for a list of the available parameters.

For example, you may be only interested in flavors that have at least 4GB of
memory:

```php
$fourGbFlavors = $compute->FlavorList(true, array('minRam' => 4096));
```