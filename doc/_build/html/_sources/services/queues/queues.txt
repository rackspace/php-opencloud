Queues
======

A note on Client IDs
--------------------

For most of the operations in Cloud Queues, you must specify a **Client ID**
which will be used as a unique identifier for the process accessing this
Queue. This is basically a UUID that must be unique to each client
accessing the API - it can be an arbitrary string.

.. code-block:: php

    $service->setClientId();

    echo $service->getClientId();

If you call ``setClientId`` without any parameters, a UUID is
automatically generated for you.


List queues
-----------

This operation lists queues for the project. The queues are sorted alphabetically by name.

.. code-block:: php

  $queues = $service->listQueues();

  foreach ($queues as $queue) {
      echo $queue->getName() , PHP_EOL;
  }


Filtering lists
~~~~~~~~~~~~~~~

You can also filter collections using the following query parameters:

+----------+-------+---------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Name     | Style | Type    | Description                                                                                                                                                                                    |
+==========+=======+=========+================================================================================================================================================================================================+
| marker   | Query | ​String  | Specifies the name of the last queue received in a previous request, or none to get the first page of results. Optional.                                                                       |
+----------+-------+---------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| limit    | Query | Integer | Specifies the number of queues to return. The default value for the number of queues returned is 10. If you do not specify this parameter, the default number of queues is returned. Optional. |
+----------+-------+---------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| detailed | Query | ​Boolean | Determines whether queue metadata is included in the response. The default value for this parameter is false, which excludes the metadata. Optional.                                           |
+----------+-------+---------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

.. code-block:: php

  $queues = $service->listQueues(array('detailed' => false));


Create queue
------------

The only parameter required is the name of the queue you're creating. The name
must not exceed 64 bytes in length, and it is limited to US-ASCII letters,
digits, underscores, and hyphens.

.. code-block:: php

  $queue = $service->createQueue('new_queue');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Queues/create-queue.php>`_


Find queue details
------------------

.. code-block:: php

  /** @var $queue OpenCloud\Queues\Resource\Queues */
  $queue = $service->getQueue('{name}');


Check queue existence
---------------------

This operation verifies whether the specified queue exists by returning
``TRUE`` or ``FALSE``.

.. code-block:: php

  if ($service->hasQueue('new_queue')) {
      // do something
  }


Update queue metadata
---------------------

This operation replaces any existing metadata document in its entirety.
Ensure that you do not accidentally overwrite existing metadata that you
want to retain. If you want to *append* metadata, ensure you merge a new
array to the existing values.

.. code-block:: php

  $queue->saveMetadata(array(
      'foo' => 'bar'
  ));


Retrieve the queue metadata
---------------------------

This operation returns metadata, such as message TTL, for the queue.

.. code-block:: php

  $metadata = $queue->retrieveMetadata();
  print_r($metadata->toArray());


Get queue stats
---------------

This operation returns queue statistics, including how many messages are
in the queue, categorized by status.

.. code-block:: php

  $queue->getStats();

Delete queue
------------

.. code-block:: php

  $queue->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Queues/delete-queue.php>`_
