Temporary URLs
==============

Temporary URLs allow you to create time-limited Internet addresses that
allow you to grant access to your Cloud Files account. Using Temporary
URL, you may allow others to retrieve or place objects in your
containers - regardless of whether they're CDN-enabled.


Set "temporary URL" metadata key
--------------------------------

You must set this "secret" value on your account, where it can be used
in a global state:

.. code-block:: php

    $account = $service->getAccount();
    $account->setTempUrlSecret('my_secret');

    echo $account->getTempUrlSecret();

The string argument of ``setTempUrlSecret()`` is optional - if left out,
the SDK will generate a random hashed secret for you.

Get the executable PHP script for this example:

* `Specify a URL secret <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/set-account-temp-url-secret-specified.php>`_
* `Generate random URL secret <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/set-account-temp-url-secret.php>`_


Create a temporary URL
----------------------

Once you've set an account secret, you can create a temporary URL for
your object. To allow GET access to your object for 1 minute:

.. code-block:: php

  $object->getTemporaryUrl(60, 'GET');


To allow PUT access for 1 hour:

.. code-block:: php

  $object->getTemporaryUrl(360, 'PUT');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/create-object-temporary-url.php>`_


Override TempURL file names
---------------------------

Override tempURL file names simply by adding the filename parameter to the url:

.. code-block:: php

    $tempUrl = $object->getTemporaryUrl(60, 'GET');    
    $url = $tempUrl.'&filename='.$label;


Hosting HTML sites on CDN
=========================

.. include:: rs-only.rst

To host a static (i.e. HTML) website on Cloud Files, you must follow
these steps:

1. CDN-enable a container:

.. code-block:: php

  $container = $service->getContainer('html_site');
  $container->enableCdn();

2. Upload all HTML content. You can use nested directory structures.

.. code-block:: php

  $container->uploadObjects(array(
      array('name' => 'index.html', 'path' => 'index.html'),
      array('name' => 'contact.html', 'path' => 'contact.html'),
      array('name' => 'error.html', 'path' => 'error.html'),
      array('name' => 'styles.css', 'path' => 'styles.css'),
      array('name' => 'main.js', 'path' => 'main.js'),
  ));

3. Tell Cloud Files what to use for your default index page like this:

.. code-block:: php

  $container->setStaticIndexPage('index.html');

4. (Optional) Tell Cloud Files which error page to use by default:

.. code-block:: php

  $container->setStaticErrorPage('error.html');


Bear in mind that steps 3 & 4 do not upload content, but rather specify
a reference to an existing page/CloudFiles object.
