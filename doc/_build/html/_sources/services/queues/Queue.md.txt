1. Introduction
---------------

A Queue is an entity that holds messages. Ideally, a queue is created
per work type. For example, if you want to compress files, you would
create a queue dedicated to this job. Any application that reads from
this queue would only compress files.

2. Setup
--------

.. code:: php

    $service = $client->queuesService('cloudQueues', 'ORD');

3. Client IDs
-------------

With most of Marconi's operation, you must specify a **Client ID** which
will be used as a unique identifier for the process accessing this
Queue. This is basically a UUID that must be unique to each client
accessing the API - it can be an arbitrary string.

.. code:: php

    $service->setClientId();

    echo $service->getClientId();

If you call ``setClientId`` without any parameters, a UUID is
automatically generated for you.

4. List queues
--------------

4.1 Description
~~~~~~~~~~~~~~~

This operation lists queues for the project. The queues are sorted
alphabetically by name.

4.2 Parameters
~~~~~~~~~~~~~~

\|Name\|Style\|Type\|Description\| \|----\|-----\|----\|-----------\|
\|marker\|Query\|​String\|Specifies the name of the last queue received
in a previous request, or none to get the first page of results.
Optional.\| \|limit\|Query\|Integer\|Specifies the number of queues to
return. The default value for the number of queues returned is 10. If
you do not specify this parameter, the default number of queues is
returned. Optional.\| \|detailed\|Query\|​Boolean\|Determines whether
queue metadata is included in the response. The default value for this
parameter is false, which excludes the metadata. Optional.\|
\|----\|-----\|----\|-----------\|

4.3 Code sample
~~~~~~~~~~~~~~~

.. code:: php

    $queues = $service->listQueues();

    while ($queue = $queues->next()) {
        echo $queue->getName() . PHP_EOL;
    }

5. Create queue
---------------

5.1 Description
~~~~~~~~~~~~~~~

This operation creates a new queue.

5.2 Parameters
~~~~~~~~~~~~~~

A string representation of the name for your new Queue. The name must
not exceed 64 bytes in length, and it is limited to US-ASCII letters,
digits, underscores, and hyphens.

5.3 Code sample
~~~~~~~~~~~~~~~

.. code:: php

    $queue = $service->createQueue('new_queue');

6. Retrieve queue
-----------------

6.1 Description
~~~~~~~~~~~~~~~

Returns a ``Queue`` object for use.

6.2 Parameters
~~~~~~~~~~~~~~

Queue name.

6.3 Code sample
~~~~~~~~~~~~~~~

.. code:: php

    $queue = $service->getQueue('new_queue');

7. Check queue existence
------------------------

7.1 Description
~~~~~~~~~~~~~~~

This operation verifies whether the specified queue exists by returning
``TRUE`` or ``FALSE``.

7.2 Parameters
~~~~~~~~~~~~~~

7.3 Code sample
~~~~~~~~~~~~~~~

.. code:: php

    if ($service->hasQueue('new_queue')) {
        // do something
    }

8. Update queue metadata (permanently to the API)
-------------------------------------------------

4.1 Description
~~~~~~~~~~~~~~~

This operation replaces any existing metadata document in its entirety.
Ensure that you do not accidentally overwrite existing metadata that you
want to retain. If you want to *append* metadata, ensure you merge a new
array to the existing values.

4.2 Parameters
~~~~~~~~~~~~~~

Hash of key pairs.

4.3 Code sample
~~~~~~~~~~~~~~~

.. code:: php

    $queue->saveMetadata(array(
        'foo' => 'bar'
    ));

9. Retrieve the queue metadata (fresh from the API)
---------------------------------------------------

4.1 Description
~~~~~~~~~~~~~~~

This operation returns metadata, such as message TTL, for the queue.

4.2 Parameters
~~~~~~~~~~~~~~

None.

4.3 Code sample
~~~~~~~~~~~~~~~

.. code:: php

    $metadata = $queue->retrieveMetadata();

    print_r($metadata->toArray());

10. Get queue stats
-------------------

4.1 Description
~~~~~~~~~~~~~~~

This operation returns queue statistics, including how many messages are
in the queue, categorized by status.

4.2 Parameters
~~~~~~~~~~~~~~

None.

4.3 Code sample
~~~~~~~~~~~~~~~

.. code:: php

    $queue->getStats();

