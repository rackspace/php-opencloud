php-opencloud
=============
PHP SDK for OpenStack/Rackspace APIs

See the COPYING file for license and copyright information.

NOTE: this is currently in a prototype state and is evolving rapidly.
It is NOT recommended for production use at this time; however,
you're welcome to make contributions and suggestions.

The PHP SDK should work with most OpenStack-based cloud deployments,
though it specifically targets the Rackspace public cloud. In
general, whenever a Rackspace deployment is substantially different
than a pure OpenStack one, a separate Rackspace subclass is provided
so that you can still use the SDK with a pure OpenStack instance
(for example, see the `OpenStack` class (for OpenStack) and the
`Rackspace` subclass).

Getting Started with OpenStack/Rackspace
----------------------------------------
To sign up for a Rackspace Cloud account, go to
http://www.rackspace.com/cloud and follow the prompts.

If you are working with an OpenStack deployment, you can find more
information at http://www.openstack.org.

### Requirements

We are not able to test and validate every possible combination of PHP
versions and supporting libraries, but here's our recommended minimum
version list:

* PHP 5.3
* CURL extensions to PHP

### Installation

Move the files in the `lib/` directory to a location in your PHP's
`include_path` or, conversely, set the `include_path` to point to the
location of the `lib/` files. From within a PHP program, for example,
you can use:

    ini_set('include_path', './lib:'.ini_get('include_path'));

This prepends the local `./lib` directory to the existing `include_path`
value.

### NOTE

This version supports the following components:

* OpenStack Nova (Rackspace Cloud Servers)
* OpenStack Swift (Rackspace Cloud Files, which includes CDN extensions)
* Rackspace Cloud Databases

Authentication
--------------
Before you can use any of the cloud services, you must authenticate
using a connection object. This object establishes a relationship
between a user and a single Keystone identity endpoint URL. There are
two different connection services provided: OpenStack and Rackspace
(hopefully, there will be more in the future, and developers are
encouraged to contribute theirs).

To use an OpenStack service, use

    require('openstack.inc');

To use the Rackspace public cloud, use:

    require('rackspace.inc');

Once you've included the desired connection class, you can proceed
to establish the connection. For OpenStack clouds, provide the
username and password:

    require('openstack.inc');
	$conn = new OpenCloud\OpenStack(
		'https://example.com/v2/identity',
		array(
			'username' => 'your username',
			'password' => 'your Keystone password'
		));

If you are using Rackspace's authentication, you need to pass your
API key and tenant ID instead:

    require('rackspace.inc');
	$conn = new OpenCloud\Rackspace(
		'https://example.com/v2/identity',
		array(
			'username' => 'your username',
			'apiKey' => 'your API key',
			'tenantName' => 'your tenant ID'
		));

The connection object can be re-used at will (so long as you're
communicating with the same endpoint) and must be passed to other
data objects.

Quick Reference - ObjectStore (Swift/Cloud Files)
-------------------------------------
These examples all assume a `$conn` object created via one of the
authentication methods outlined above.

### Attach an ObjectStore instance

    $ostore = $conn->ObjectStore({name}, {region}, {urltype});

where `{name}` is the service name (e.g., "cloudFiles"),
`{region}` is the region identifier (e.g., "DFW" or "LON"), and
`{urltype}` is the URL type (normally `'publicURL'` by default, but
can be changed if you're working with an internal staging instance).

You can simplify this by setting the defaults:

    $conn->SetDefaults('ObjectStore', {name}, {region}, {urltype});
    $ostore = $conn->ObjectStore(); // uses default values

### Create a new container

Using the `$ostore` object created above:

    $mycontainer = $ostore->Container();
    $mycontainer->name = 'SuperContainer';
    $mycontainer->Create();

Or, you can simplify things somewhat:

    $mycontainer = $ostore->Container();
    $mycontainer->Create(array('name'=>'SuperContainer'));

### Retrieve an existing container

Using the `$ostore` object create above:

    $myoldcontainer = $ostore->Container('SomeOldContainer');

### Get a list of all containers (using a Collection object)

When php-opencloud retrieves a _list_ of something, it returns a `Collection`
object that can be navigated via the `->First()`, `->Next()`, and `->Size()`
methods.

Using the `$ostore` object created above:

    $containerlist = $ostore->ContainerList();
    while($container = $containerlist->Next()) {
        // do something with the container
        printf("Container %s has %u bytes\n",
            $container->name, $container->bytes);
    }

The `->First()` method on a Collection resets to the beginning of the list,
while the `->Next()` method retrieves the next object in the collection,
returning `FALSE` when it's done.

### Delete a container

Note that you cannot delete a container unless it is empty (i.e., all the
objects in it have also been deleted). Example:

    $mycontainer->Delete();

### Create a new object in a container

    $mypicture = $mycontainer->Object();
    $mypicture->Create(
        array('name'=>'picture.jpg', 'type'=>'image/jpeg'),
        '/path/to/mypicture.jpg');

The first parameter to `Create()` is a hashed array of values. `name` is the
object name, and `type` is the Content-Type.

The second parameter to `Create()` is an optional filename; the data will be
streamed from the local file to the stored Object.

If you prefer, you can create the object in-memory first:

    $mypicture = $mycontainer->Object();
    $mypicture->SetData(file_get_contents('/path/to/picture.jpg'));
    $mypicture->name = 'potato.jpg';
    $mypicture->content_type = 'image/jpeg';
    $mypicture->Create();

### Save an object to a file

Because objects are sometimes enormous, they are not retrieved directly
from the object store; instead, the metadata about the object is returned.
For example, this call:

    $myphoto = $mycontainer->Object('photo.jpg');

does not actually retrieve the photo data; it retrieve the metadata about
the photo. To save the data to a file, use this method:

    $myphoto->SaveToFilename('/path/to/yourfile.jpg');

### Deleting an object

Very simple:

    $myobject->Delete();

### List the objects in a container

Remember the Collection object discussed above? Here it is again:

    $objlist = $mycontainer->ObjectList();
    while($object = $objlist->Next()) {
        printf("Object %s size=%u\n", $object->name, $object->bytes);
    }

### Filtering lists

Most functions that return a collection can be passed an associative array of
values that are used as filters. For example, see [the API guide](http://docs.rackspace.com/files/api/v1/cf-devguide/content/List_Objects-d1e1284.html)
for a list of filters. Here's an example: we'll only list objects whose
names are prefixed with `photo`:

    $objlist = $mycontainer->ObjectList(array('prefix'=>'photo'));

Quick Reference - Compute (Nova/Cloud Servers)
----------------------------------------------

These examples all assume a connection object `$conn` created using either the
`OpenStack` or `Rackspace` classes (see "Authentication," above).

### Connecting to a Compute instance

To connect to a Compute instance, you need to specify the name of the service,
the region, and the URL type:

    $compute = $conn->Compute('cloudServersOpenStack', 'DFW', 'publicURL');

This can get complicated, but you can simplify things by relying upon the
default values. For example, the default URL type is `'publicURL'`, so you
can leave that off:

    $compute = $conn->Compute('cloudServersOpenStack', 'DFW');

and you can set the defaults once and not have to change it:

    $conn->SetDefaults('Compute', 'cloudServersOpenStack', 'DFW', 'publicURL');

So this code:

    $compute = $conn->Compute();

connects to the default service and region, while this one:

    $computeORD = $conn->Compute(NULL, 'ORD');

connects to the same service, but on the `'ORD'` endpoint.

### Working with lists (the Collection object)

A `Collection` is a special type of object that manages lists. For example,
a list of _flavors_ is returned as a `Collection` object.

Collections have three primary methods:

* `Next()` - returns the next item in the collection, or `FALSE` at the end,
* `First()` - resets the pointer to the first item in the collection, and
* `Size()` - returns the number of items in the collection.

### List Flavors

    $flavorlist = $compute->FlavorList();
    while($flavor = $flavorlist->Next())
        printf("Flavor: %s RAM=%d\n", $flavor->name, $flavor->ram);

### List Images

    $imagelist = $compute->ImageList();
    while($image = $imagelist->Next())
        printf("Image: %s\n", $image->name);

### List Servers

    $serverlist = $compute->ServerList();
    while($server = $serverlist->Next())
        printf("Server: %s\n", $server->name);

### Create a new server

First, we get an empty server object:

    $myserver = $compute->Server();

Then, we use the `Create()` method, which requires a name as well as a
`Flavor` and an `Image` object:

    $myflavor = $compute->Flavor('3');
    $myimage = $compute->Image('c195ef3b-9195-4474-b6f7-16e5bd86acd0');
    $myserver->Create(array(
        'name' => 'MyServerName',
        'image' => $myimage,
        'flavor' => $myflavor));

This updates the `Server` object with the root password for the new
server:

    printf("The root password is %s\n", $myserver->adminPass);

### Performing server actions

#### Create an image from a server

    $myserver->CreateImage('imageName');

#### Reboot the server

    $myserver->Reboot('hard'); // can be 'soft'

#### Resize the server

    $myserver->Resize($myflavor); // requires a Flavor
    // once ready, then either
    $myserver->ResizeConfirm();
    // or
    $myserver->ResizeRevert();

### Wait for server state change

The `WaitFor()` method is provided as a way to wait for the server build
to complete (or for another action to finish). For example, you can
reboot a server and wait for it to become active again:

    $myserver->Reboot();            // does a 'soft' reboot by default
    $myserver->WaitFor('ACTIVE');    // waits for ACTIVE state (or ERROR)
    printf("Server %s is available after reboot\n", $myserver->name);

If you don't want to wait forever, you can specify a timeout:

    $myserver->Reboot();
    $myserver->WaitFor('ACTIVE', 300);
    if ($myserver->status == 'REBOOT')
        die('I waited five minutes and the server did not reboot yet');

If you want to provide visual feedback while waiting, you can provide a
callback function that is passed the server object:

    function myProgressMeter($server) {
        printf("Server build is %d%% complete\n", $server->progress);
    }
    $myserver->Create(...);
    $myserver->WaitFor('ACTIVE', 600, 'myProgressMeter');

Further Reading
---------------
There is a complete (auto-generated) API reference manual in the
docs/api directory. Start with docs/api/index.html.

See the `HOWTO.md` file for instructions on regenerating the documentation
and running tests.

See the `smoketest.php` file for some simple, working examples.

The `samples/` directory has a collection of tested, working sample code.
Note that these may create objects in your cloud that you could be
charged for.
