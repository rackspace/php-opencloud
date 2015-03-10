Snapshots
=========

Create a snapshot
-----------------

A ``Snapshot`` object is created from the Cloud Block Storage service.
However, it is associated with a volume, and you must specify a volume
to create one:

.. code-block:: php

  // New instance of OpenCloud\Volume\Resource\Snapshot
  $snapshot = $service->snapshot();

  // Send to API
  $snapshot->create(array(
      'display_name' => 'Name that snapshot',
      'volume_id'    => $volume->id
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/create-snapshot.php>`_


List snapshots
--------------

.. code-block:: php

  $snapshots = $service->snapshotList();

  foreach ($snapshots as $snapshot) {
      /** @param $snapshot OpenCloud\Volume\Resource\Snapshot */
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/list-snapshots.php>`_


To get details on a single snapshot
-----------------------------------

.. code-block:: php

  $snapshot = $dallas->snapshot('{snapshotId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/get-snapshot.php>`_


To delete a snapshot
--------------------

.. code-block:: php

  $snapshot->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Volume/delete-snapshot.php>`_
