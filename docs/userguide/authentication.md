Authentication
==============

Before you can do anything with php-opencloud, you must authenticate
with your cloud provider. This is simultaneously the simplest and sometimes
the most frustrating part of the whole process.

It is simple because you only need to have two pieces of information:

* The authentication endpoint of your cloud provider
* The credentials required to authenticate

Once you have authenticated, the cloud provider will respond with a
[service catalog](http://docs.rackspace.com/auth/api/v2.0/auth-client-devguide/content/Sample_Request_Response-d1e64.html) 
that contains links to all the
various provider's services. All you need to do is specify which service
to use, and the library will take care of finding the appropriate links.

First, however, you need to know if you're using the Rackspace cloud
or an OpenStack cloud, and include the proper top-level file. These are
called *Connection* classes, because they establish a connection between an
authorized user and a specific cloud deployment. They are required for any use of 
<b>php-opencloud</b>.

## Authenticating against OpenStack clouds

First, include the `openstack.inc` top-level file:

    <?php
    include('openstack.inc');

Next, create an `OpenStack` object with the proper credentials:

    $endpoint = 'https://your-cloud-provider/path';
    $credentials = array(
        'username' => 'YOUR USERNAME',
        'password' => 'YOUR PASSWORD',
        'tenantName' => 'YOUR TENANT NAME'
    );
    $cloud = new OpenCloud\OpenStack($endpoint, $credentials);

(Note that the `tenantName` value may not be required for all installations.)

In this example, `$credentials` is an associative array (or "hashed" array). The
keys are `username` and `password` and their values are your assigned OpenStack user
name and password, respectively. 

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

Replace the values for `username`, `tenantName` (sometimes called the *tenant ID* or
the *customer ID* in Rackspace documentation), and `apiKey` with the values for your
account. If you don't have an API key, see
[@TODO need link for API key].

Note that Rackspace UK users will have a different `$endpoint` than US users.

## Credential Caching

Note that you only need to authenticate once; the <b>php-opencloud</b> library caches
your credentials internally and will reuse them until they expire, at which point it
will automatically re-authenticate. The only time you will need to create a new
`OpenStack` or `Rackspace` object is if your credentials change (for example, if your
password changes) or to use a different account.

Repeatedly re-authenticating can create a load on the authentication servers and 
potentially degrade performance for all users. 

What's next?
------------

See [Working with services](services.md) for the next steps.
