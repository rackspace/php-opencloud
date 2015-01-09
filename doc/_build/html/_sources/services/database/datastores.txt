Datastores
==========

Listing datastores
------------------

You can list out all the datastores available as shown below:

.. code-block:: php

  $datastores = $service->datastoreList();
  foreach ($datastores as $datastore) {
      /** @var $datastore OpenCloud\Database\Resource\Datastore **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/list-datastores.php>`__


Retrieving a datastore
----------------------

You can retrieve a specific datastore's information, using its ID, as
shown below:

.. code-block:: php

  /** @var OpenCloud\Database\Resource\Datastore **/
  $datastore = $service->datastore('{datastoreId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/get-datastore.php>`__


Listing datastore versions
--------------------------

You can list out all the versions available for a specific datastore, as
shown below:

.. code-block:: php

  $versions = $datastore->versionList();
  foreach ($versions as $version) {
      /** @var $version OpenCloud\Database\Resource\DatastoreVersion **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/list-datastore-versions.php>`__


Retrieving a datastore version
------------------------------

You a retrieve a specific datastore version, using its ID, as shown
below:

.. code-block:: php

  $datastoreVersion = $datastore->version('{versionId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/get-datastore-version.php>`__
