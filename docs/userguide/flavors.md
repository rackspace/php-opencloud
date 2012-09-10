Working with Flavors
====================

A *flavor* is a named definition of certain server parameters such as the
amount of RAM and disk space available. (There are other parameters set via
the flavor, such as the amount of disk space and the number of virtual CPUs,
but a discussion of those is too in-depth for a simple "getting started" guide
like this one.)

A `Flavor` object is generated (as one might suspect) from the `Flavor()`
method on the `Compute` object:

    $flavor = $compute->Flavor();

This is an empty flavor, and not very useful. Normally, you'll retrieve
a flavor by its ID:

    $flavor = $compute->Flavor(2);

The ID can be either a full UUID or simply an integer (as shown above). The
actual format will depend upon your cloud provider.

A list of flavors is provided by the…wait for it…`FlavorList` object,
which comes from (ta-da!) the `FlavorList()` method:

    $flavors = $compute->FlavorList();
    while($flavor = $flavors->Next())
        printf("Flavor %s has %dMB of RAM and %dGB of disk\n",
            $flavor->name, $flavor->ram, $flavor->disk);

### Flavor details

By default, the `FlavorList()` method returns full details on all flavors.
However, because of the overhead involved in retrieving all the details,
this function can be slower than a simple list. You can supply an optional
boolean parameter to the `FlavorList()` method to determine whether or not
the flavor details are included:

    $fastlist = $compute->FlavorList(FALSE); // name + id only
    $slowlist = $compute->FlavorList(TRUE);  // include all details

### Filtering flavors

The optional second parameter to the `FlavorList()` method is an
associative array of filter parameters. [See here for a complete list](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/List_Flavors-d1e4188.html)

For example, you may be only interested in flavors that have at least 4GB of
memory:

    $biglist = $compute->FlavorList(TRUE, array('minRam'=>4096));

Or perhaps only flavors that have at least 200GB of disk:

    $biglist = $compute->FlavorList(TRUE, array('minDisk'=>200));

These filters can, of course, be combined:

    $mylist = $compute->FlavorList(
        TRUE,
        array('minRam'=>4000, 'minDisk'=>200));

### Examples

The file `samples/compute/flavors.php` has some examples of working with
Flavors.
