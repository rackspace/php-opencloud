Volumes
=======

Create a volume
---------------

To create a volume, you must specify its size (in gigabytes). All other
parameters are optional:

.. code-block:: php

  // Create instance of OpenCloud\Volume\Resource\Volume
  $volume = $service->volume();

  $volume->create(array(
      'size'                => 200,
      'volume_type'         => $service->volumeType('<volume_type_id>'),
      'display_name'        => 'My Volume',
      'display_description' => 'Used for large object storage'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/create-volume.php>`_


List volumes
------------

.. code-block:: php

  $volumes = $service->volumeList();

  foreach ($volumes as $volume) {
      /** @param $volumeType OpenCloud\Volume\Resource\Volume */
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/list-volumes.php>`_


Get details on a single volume
------------------------------

If you specify an ID on the ``volume()`` method, it retrieves
information on the specified volume:

.. code-block:: php

  $volume = $dallas->volume('<volume_id>');
  echo $volume->size;

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/get-volume.php>`_


To delete a volume
------------------

.. code-block:: php

  $volume->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/delete-volume.php>`_


Attach a volume to a server
---------------------------

.. code-block:: php

  // retrieve server
  $computeService = $client->computeService('{catalogName}', '{region}');
  $server = $computeService->server('{serverId}');

  // attach volume
  $server->attachVolume($volume, '{mountPoint}')

The ``{mountPoint}`` is the location on the server on which to mount
the volume (usually ``/dev/xvhdd`` or similar). You can also supply
``'auto'`` as the mount point, in which case the mount point will be
automatically selected for you. ``auto`` is the default value for
``{mountPoint}``, so you do not actually need to supply anything for
that parameter.


Detach a volume from a server
-----------------------------

.. code-block:: php

  $server->detachVolume($volume);
