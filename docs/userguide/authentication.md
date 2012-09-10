Authentication
==============

Before you can do anything with _php-opencloud_, you must authenticate
with your cloud provider. This is simultaneously the simplest and sometimes
the most frustrating part of the whole process.

It is simple because you only need to have two pieces of information:

* The authentication endpoint of your cloud provider, and
* The credentials required to authenticate.

Once you have authenticated, the cloud provider will respond with a
[service catalog](link to openstack.org) that contains links to all the
various provider's services. All you need to do is specify which service
to use, and the library will take care of finding the appropriate links.

First, however, you need to know if you're using the Rackspace cloud
or an OpenStack cloud, and include the proper top-level file. These are
called *Connection* classes, and they are required for any use of *php-opencloud*.

## Authenticating against OpenStack clouds

First, include the `openstack.inc` top-level file:

    <?php
    include('openstack.inc');

Next, create an `OpenStack` object with the proper credentials:

    $endpoint = 'https://your-cloud-provider/path';
    $credentials = array(
        'username' => 'YOUR USERNAME',
        'password' => 'YOUR PASSWORD'
    );
    $cloud = new OpenCloud\OpenStack($endpoint, $credentials);

Make sure you replace `'YOUR USERNAME'` and `'YOUR PASSWORD'` with
your assigned username and password.

## Authenticating against the Rackspace public cloud

First, include the `rackspace.inc` top-level file:

    <?php
    include('rackspace.inc');

Next, create a `Rackspace` object with the proper credentials:

    $endpoint = 'https://identity.api.rackspacecloud.com/v2.0/';
    $credentials = array(
        'username' => 'YOUR USERNAME',
        'tenantName' => 'YOUR TENANT NAME',
        'apiKey' => 'YOUR API KEY'
    );
    $cloud = new OpenCloud\Rackspace($endpoint, $credentials);

Replace `'YOUR USERNAME'` and the other values with those that have
been assigned to you by your Provider.

What's next?
------------

See [Working with services](services.md) for the next steps.
