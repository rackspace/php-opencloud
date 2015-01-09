Setup
-----

.. code:: php

    use OpenCloud\Rackspace;

    $client = new Rackspace(RACKSPACE_US, array(

    ));

    $service = $client->objectStoreService('cloudFiles', 'IAD'); # Second argument is the region you want

Temporary URLs
--------------

Temporary URLs allow you to create time-limited Internet addresses that
allow you to grant access to your Cloud Files account. Using Temporary
URL, you may allow others to retrieve or place objects in your
containers - regardless of whether they're CDN-enabled.

Set "temporary URL" metadata key
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You must set this "secret" value on your account, where it can be used
in a global state:

.. code:: php

    $account = $service->getAccount();
    $account->setTempUrlSecret('my_secret');

    echo $account->getTempUrlSecret();

The string argument of ``setTempUrlSecret()`` is optional - if left out,
the SDK will generate a random hashed secret for you.

Create a temporary URL
~~~~~~~~~~~~~~~~~~~~~~

Once you've set an account secret, you can create a temporary URL for
your object. To allow GET access to your object for 1 minute:

.. code:: php

    $object->getTemporaryUrl(60, 'GET');

To allow PUT access for 1 hour:

.. code:: php

    $object->getTemporaryUrl(360, 'PUT');

Hosting websites on CloudFiles
------------------------------

To host a static (i.e. HTML) website on CloudFiles, you must follow
these steps:

1. CDN-enable a container
2. Upload all HTML content. You can use nested directory structures.
3. Tell CloudFiles what to use for your default index page like this:

.. code:: php

    $container->setStaticIndexPage('index.html');

4. (Optional) Tell CloudFiles which error page to use by default:

.. code:: php

    $container->setStaticErrorPage('error.html');

Bear in mind that steps 3 & 4 do not upload content, but rather specify
a reference to an existing page/CloudFiles object.
