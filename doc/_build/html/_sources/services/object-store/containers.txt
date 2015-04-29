Containers
==========

Create container
----------------

To create a new container, you just need to define its name:

.. code-block:: php

    $container = $service->createContainer('my_amazing_container');

If the response returned is ``FALSE``, there was an API error - most
likely due to the fact you have a naming collision.

Container names must be valid strings between 0 and 256 characters.
Forward slashes are not currently permitted.

.. note::

  When working with names that contain non-standard alphanumerical characters
  (such as spaces or non-English characters), you must ensure they are encoded
  with `urlencode <http://php.net/urlencode>`_ before passing them in

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/create-container.php>`_


List containers
---------------

.. code-block:: php

  $containers = $service->listContainers();

  foreach ($containers as $container) {
      /** @param $container OpenCloud\ObjectStore\Resource\Container */
      printf("Container name: %s\n", $container->name);
      printf("Number of objects within container: %d\n", $container->getObjectCount());
  }

Container names are sorted based on a binary comparison, a single
built-in collating sequence that compares string data using SQLite's
memcmp() function, regardless of text encoding.

The list is limited to 10,000 containers at a time. To work with larger
collections, please read the next section.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/list-containers.php>`_


Filtering large collections
~~~~~~~~~~~~~~~~~~~~~~~~~~~

When you need more control over collections of containers, you can filter the
results and return back a subset of the total collection by using the ``marker``
and ``end_marker`` parameters. The former parameter (``marker``) tells
the API where to begin the list, and the latter (``end_marker``) tells
it where to end the list. You may use either of them independently or
together.

You may also use the ``limit`` parameter to fix the number of
containers returned.

To list a set of containers between two fixed points:

.. code-block:: php

  $someContainers = $service->listContainers(array(
      'marker'     => 'container_55',
      'end_marker' => 'container_2001'
  ));

Or to return a limited set:

.. code-block:: php

  $someContainers = $service->listContainers(array('limit' => 560));


Get container
-------------

To retrieve a certain container:

.. code-block:: php

  /** @param $container OpenCloud\ObjectStore\Resource\Container */
  $container = $service->getContainer('{containerName}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-container.php>`_


Retrieve a container's name
~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  $name = $container->name;


Retrieve a container's object count
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  $count = $container->getObjectCount();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-container-object-count.php>`_


Retrieve a container's total bytes used
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  $bytes = $container->getBytesUsed();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-container-bytes-used.php>`_


Delete container
----------------

Deleting an empty container is easy:

.. code-block:: php

  $container->delete();


Please bear mind that you must delete all objects inside a container
before deleting it. This is done for you if you set the
``$deleteObjects`` parameter to ``TRUE`` like so:

.. code-block:: php

  $container->delete(true);


You can also `delete all objects <#deleting-all-objects-inside-a-container>`_
first, and then call ``delete``.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/delete-container.php>`_


Deleting all objects inside a container
---------------------------------------

.. code-block:: php

  $container->deleteAllObjects();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/delete-container-recursive.php>`_


Create or update container metadata
-----------------------------------

.. code-block:: php

  $container->saveMetadata(array(
      'Author' => 'Virginia Woolf',
      'Published' => '1931'
  ));

Please bear in mind that this action will set metadata to this array -
overriding existing values and wiping those left out. To *append* values
to the current metadata:

.. code-block:: php

  $metadata = $container->appendToMetadata(array(
      'Publisher' => 'Hogarth'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/set-container-metadata.php>`_


Container quotas
----------------

The ``container_quotas`` middleware implements simple quotas that can be
imposed on Cloud Files containers by a user. Setting container quotas
can be useful for limiting the scope of containers that are delegated to
non-admin users, exposed to formpost uploads, or just as a self-imposed
sanity check.

To set quotas for a container:

.. code-block:: php

  use OpenCloud\Common\Constants\Size;

  $container->setCountQuota(1000);
  $container->setBytesQuota(2.5 * Size::GB);

And to retrieve them:

.. code-block:: php

  echo $container->getCountQuota();
  echo $container->getBytesQuota();

Get the executable PHP scripts for this example:

* `Set bytes quota <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-container-bytes-quota.php>`_
* `Set count quota <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-container-count-quota.php>`_


Access log delivery
-------------------

To view your object access, turn on Access Log Delivery. You can use
access logs to analyze the number of people who access your objects,
where they come from, how many requests for each object you receive, and
time-based usage patterns (such as monthly or seasonal usage).

.. code-block:: php

  $container->enableLogging();
  $container->disableLogging();


Syncing containers
------------------

You can synchronize local directories with your CloudFiles/Swift
containers very easily. When you do this, the container will mirror
exactly the nested file structure within your local directory:

.. code-block:: php

  $container->uploadDirectory('/home/user/my-blog');

There are four scenarios you should be aware of:

+------------------------+-----------------------+----------------------+--------------------------------+
| Local                  | Remote                | Comparison           | Action                         |
+========================+=======================+======================+================================+
| File exists            | File exists           | Identical checksum   | No action                      |
+------------------------+-----------------------+----------------------+--------------------------------+
| File exists            | File exists           | Different checksum   | Local file overwrites remote   |
+------------------------+-----------------------+----------------------+--------------------------------+
| File exists            | File does not exist   | -                    | Local file created in Swift    |
+------------------------+-----------------------+----------------------+--------------------------------+
| Files does not exist   | File exists           | -                    | Remote file deleted            |
+------------------------+-----------------------+----------------------+--------------------------------+
