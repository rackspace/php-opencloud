Images
======

.. note::

  **Images on Rackspace servers:** with standard servers, the entire disk
  (OS and data) is captured in the image. With Performance servers, only the s
  ystem disk is captured in the image. The data disks should be backed up using
  Cloud Backup or Cloud Block Storage to ensure availability in case you need
  to rebuild or restore a server.


List images
-----------

Below is the simplest usage for retrieving a list of images:

.. code-block:: php

  $images = $service->imageList();

  foreach ($images as $image) {

  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Compute/list_images.php>`_


Detailed results
~~~~~~~~~~~~~~~~

By default, the only fields returned in a list call are `id` and `name`, but
you can enable more detailed information to be result by passing in `true` as
the first argument of the call, like so:

.. code-block:: php

  $images = $service->imageList(true);


Filtering
~~~~~~~~~

You can also refine the list of images returned by providing specific filters:

+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Array key       | Description                                                                                                                                                                                                                                                                                                                                        |
+=================+====================================================================================================================================================================================================================================================================================================================================================+
| server          | Filters the list of images by server. Specify the server reference by ID or by full URL.                                                                                                                                                                                                                                                           |
+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| name            | Filters the list of images by image name.                                                                                                                                                                                                                                                                                                          |
+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| status          | Filters the list of images by status. In-flight images have a status of ``SAVING`` and the conditional progress element contains a value from 0 to 100, which indicates the percentage completion. For a full list, please consult the ``OpenCloud\Compute\Constants\ImageState`` class. Images with an ``ACTIVE`` status are available for use.   |
+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| changes-since   | Filters the list of images to those that have changed since the changes-since time. See the `official docs <http://docs.rackspace.com/servers/api/v2/cs-devguide/content/ChangesSince.html>`__ for more information.                                                                                                                               |
+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| marker          | The ID of the last item in the previous list. See the `official docs <http://docs.rackspace.com/servers/api/v2/cs-devguide/content/Paginated_Collections-d1e664.html>`__ for more information.                                                                                                                                                     |
+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| limit           | Sets the page size. See the `official docs <http://docs.rackspace.com/servers/api/v2/cs-devguide/content/Paginated_Collections-d1e664.html>`__ for more information.                                                                                                                                                                               |
+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| type            | Filters base Rackspace images or any custom server images that you have created. Can either be ``BASE`` or ``SNAPSHOT``.                                                                                                                                                                                                                           |
+-----------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

These are defined in an array and passed in as the second argument. For example,
to filter images for a particular server:

.. code-block:: php

  $images = $service->imageList(false, array('server' => '{serverId}'));


Retrieve details about an image
-------------------------------

.. code-block:: php

  $image = $service->image('{imageId}');


Delete an image
---------------

.. code-block:: php

  $image->delete();
