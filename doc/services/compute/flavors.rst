Flavors
=======

Get a flavor
------------

.. code-block:: php

  $flavor = $service->flavor('{flavorId}');


List flavors
------------

.. code-block:: php

  $flavors = $service->flavorList();

  foreach ($flavors as $flavor) {
      /** @param $flavor OpenCloud\Common\Resource\FlavorInterface */
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Compute/list_flavors.php>`_


Detailed results
~~~~~~~~~~~~~~~~

By default, the ``flavorList`` method returns full details on all flavors.
However, because of the overhead involved in retrieving all the details, this
function can be slower than might be expected. To disable this feature and
keep bandwidth at a minimum, just pass ``false`` as the first argument:

.. code-block:: php

  // Name and ID only
  $compute->flavorList(false);


Filtering
~~~~~~~~~

You can also refine the list of images returned by providing specific filters:

+-----------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Array key       | Description                                                                                                                                                                                    |
+=================+================================================================================================================================================================================================+
| minDisk         | Filters the list of flavors to those with the specified minimum number of gigabytes of disk storage.                                                                                           |
+-----------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| minRam          | Filters the list of flavors to those with the specified minimum amount of RAM in megabytes.                                                                                                    |
+-----------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| marker          | The ID of the last item in the previous list. See the `official docs <http://docs.rackspace.com/servers/api/v2/cs-devguide/content/Paginated_Collections-d1e664.html>`__ for more information. |
+-----------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| limit           | Sets the page size. See the `official docs <http://docs.rackspace.com/servers/api/v2/cs-devguide/content/Paginated_Collections-d1e664.html>`__ for more information.                           |
+-----------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

These are defined in an array and passed in as the second argument. For example,
to return all flavors over 4GB in RAM:

.. code-block:: php

  $flavors = $service->flavorList(true, array('minRam' => 4));
