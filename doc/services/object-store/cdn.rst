CDN Containers
==============

.. include:: rs-only.rst

Setup
-----

In order to interact with CDN containers, you first need to instantiate a
CDN service object:

.. code-block:: php

  $cdnService = $service->getCdnService();


List CDN-enabled containers
---------------------------

To list CDN-only containers, follow the same operation for Storage which
lists all containers. The only difference is which service object you
execute the method on:

.. code-block:: php

  $cdnContainers = $cdnService->listContainers();

  foreach ($cdnContainers as $cdnContainer) {
    /** @var $cdnContainer OpenCloud\ObjectStore\Resource\CDNContainer */
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/list-cdn-containers.php>`_


CDN-enable a container
----------------------

Before a container can be CDN-enabled, it must exist in the storage
system. When a container is CDN-enabled, any objects stored in it are
publicly accessible over the Content Delivery Network by combining the
container's CDN URL with the object name.

Any CDN-accessed objects are cached in the CDN for the specified amount
of time called the TTL. The default TTL value is 259200 seconds, or 72
hours. Each time the object is accessed after the TTL expires, the CDN
refetches and caches the object for the TTL period.

.. code-block:: php

  $container->enableCdn();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/enable-container-cdn.php>`_


CDN-disable a container
-----------------------

.. code-block:: php

  $container->disableCdn();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/disable-container-cdn.php>`_


Operations on CDN-enabled containers
------------------------------------

Once a container has been CDN-enabled, you can retrieve it like so:

.. code-block:: php

  $cdnContainer = $cdnService->cdnContainer('{containerName}');

If you already have a container object and want to avoid instantiating a new service, you can also do:

.. code-block:: php

  $cdnContainer = $container->getCdn();


Retrieve the SSL URL of a CDN container
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  $cdnContainer->getCdnSslUri();


Retrieve the streaming URL of a CDN container
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  $cdnContainer->getCdnStreamingUri();


Retrieve the iOS streaming URL of a CDN container
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The Cloud Files CDN allows you to stream video to iOS devices without
needing to convert your video. Once you CDN-enable your container, you
have the tools necessary for streaming media to multiple devices.

.. code-block:: php

  $cdnContainer->getIosStreamingUri();


CDN logging
~~~~~~~~~~~

To enable and disable logging for your CDN-enabled container:

.. code-block:: php

  $cdnContainer->enableCdnLogging();
  $cdnContainer->disableCdnLogging();


Purge CDN-enabled objects
-------------------------

To remove a CDN object from public access:

.. code-block:: php

  $object->purge();

You can also provide an optional e-mail address (or comma-delimeted list
of e-mails), which the API will send a confirmation message to once the
object has been completely purged:

.. code-block:: php

  $object->purge('jamie.hannaford@rackspace.com');
  $object->purge('hello@example.com,hallo@example.com');
