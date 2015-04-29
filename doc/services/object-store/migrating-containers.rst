Migrating containers across regions
===================================

Currently, there exists no single API operation to copy containers
across geographic endpoints. Although the API offers a ``COPY``
operation for individual files, this does not work for cross-region
copying. The SDK, however, does offer this functionality.

You **will** be charged for bandwidth between regions, so it's advisable
to use ServiceNet where possible (which is free).


Requirements
------------

-  You must install the full Guzzle package, so that the process can
   take advantage of Guzzle's batching functionality (it allows parallel
   requests to be batched for greater efficiency). You can do this by
   running:

.. code-block:: bash

  composer require guzzle/guzzle

-  Depending on the size and number of transfer items, you will need to
   raise PHP's memory limit:

.. code-block:: php

  ini_set('memory_limit', '512M');

-  You will need to enact some kind of backoff/retry strategy for rate
   limits. Guzzle comes with a convenient feature that just needs to be
   added as a normal subscriber:

.. code-block:: php

    use Guzzle\Plugin\Backoff\BackoffPlugin;

    // maximum number of retries
    $maxRetries = 10;

    // set HTTP error codes
    $httpErrors = array(500, 503, 408);

    $backoffPlugin = BackoffPlugin::getExponentialBackoff($maxRetries, $httpErrors);
    $client->addSubscriber($backoffPlugin);


This tells the client to retry up to ``10`` times for failed requests
have resulted in these HTTP status codes: ``500``, ``503`` or ``408``.


Setup
-----

You can access all this functionality by executing:

.. code-block:: php

  $ordService = $client->objectStoreService('cloudFiles', 'ORD');
  $iadService = $client->objectStoreService('cloudFiles', 'IAD');

  $oldContainer = $ordService->getContainer('old_container');
  $newContainer = $iadService->getContainer('new_container');

  $iadService->migrateContainer($oldContainer, $newContainer);


It's advisable to do this process in a Cloud Server in one of the two
regions you're migrating to/from. This allows you to use ``internalURL``
as the third argument in the ``objectStoreService`` methods like this:

.. code-block:: php

  $client->objectStoreService('cloudFiles', 'IAD', 'internalURL');


This will ensure that traffic between your server and your new IAD
container will be held over the internal Rackspace network which is
free.


Options
-------

You can pass in an array of arguments to the method:

.. code-block:: php

  $options = array(
      'read.batchLimit'  => 100,
      'read.pageLimit'   => 100,
      'write.batchLimit' => 50
  );

  $iadService->migrateContainer($oldContainer, $newContainer, $options);


Options explained
~~~~~~~~~~~~~~~~~

+------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-----------+
| Name                   | Description                                                                                                                                                                                                                                                                                                                                   | Default   |
+========================+===============================================================================================================================================================================================================================================================================================================================================+===========+
| ``read.pageLimit``     | When the process begins, it has to collect all the files that exist in the old container. It does this through a conventional ``objectList`` method, which calls the ``PaginatedIterator``. This iterator has the option to specify the page size for the collection (i.e. how many items are contained per page in responses from the API)   | 10,000    |
+------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-----------+
| ``read.batchLimit``    | After the data objects are collected, the process needs to send an individual GET request to ascertain more information. In order to make this process faster, these individual GET requests are batched together and sent in parallel. This limit refers to how many of these GET requests are batched together.                             | 1,000     |
+------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-----------+
| ``write.batchLimit``   | Once each file has been retrieved from the API, a PUT request is executed against the new container. Similar to above, these PUT requests are batched - and this number refers to the amount of PUT requests batched together.                                                                                                                | 100       |
+------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-----------+
