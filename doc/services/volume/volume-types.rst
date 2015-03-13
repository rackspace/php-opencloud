Volume Types
============

List volume types
-----------------

.. code-block:: php

  $volumeTypes = $service->volumeTypeList();

  foreach ($volumeTypes as $volumeType) {
      /** @param $volumeType OpenCloud\Volume\Resource\VolumeType */
  }


Describe a volume type
----------------------

If you know the ID of a volume type, use the ``volumeType`` method to
retrieve information on it:

.. code-block:: php

  $volumeType = $service->volumeType(1);

A volume type has three attributes:

-  ``id`` the volume type identifier
-  ``name`` its name
-  ``extra_specs`` additional information for the provider
