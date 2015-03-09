Configurations
==============

Creating a configuration
------------------------

.. code-block:: php

  /** @var $configuration OpenCloud\Database\Resource\Configuration **/
  $configuration = $service->configuration();

  $configuration->create(array(
      'name'        => 'example-configuration-name',
      'description' => 'An example configuration',
      'values'      => array(
          'collation_server' => 'latin1_swedish_ci',
          'connect_timeout' => 120
      ),
      'datastore' => array(
          'type'    => '10000000-0000-0000-0000-000000000001',
          'version' => '1379cc8b-4bc5-4c4a-9e9d-7a9ad27c0866'
      )
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/create-configuration.php>`__


Listing configurations
----------------------

You can list out all the configurations you have created as shown below:

.. code-block:: php

  $configurations = $service->configurationList();
  foreach ($configurations as $configuration) {
      /** @var $configuration OpenCloud\Database\Resource\Configuration **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/list-configurations.php>`__


Retrieving a configuration
--------------------------

You can retrieve a specific configuration, using its ID, as shown below:

.. code-block:: php

  $configuration = $service->configuration('{configId}');
  /** @var OpenCloud\Database\Resource\Configuration **/

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/get-configuration.php>`__


Updating a configuration
------------------------

You have two choices when updating a configuration:

* you can `patch a configuration <#patching-a-configuration>`__ to change only
some configuration parameters
* you can `entirely replace a configuration <#replacing-a-configuration>`__ to
replace all configuration parameters with new ones


Patching a configuration
~~~~~~~~~~~~~~~~~~~~~~~~

You can patch a configuration as shown below:

.. code-block:: php

  $configuration->patch(array(
      'values' => array(
          'connect_timeout' => 30
      )
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/patch-configuration.php>`__


Replacing a configuration
~~~~~~~~~~~~~~~~~~~~~~~~~

You can replace a configuration as shown below:

.. code-block:: php

  $configuration->update(array(
      'values' => array(
          'collation_server' => 'utf8_general_ci',
          'connect_timeout' => 60
      )
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/replace-configuration.php>`__


Deleting a configuration
------------------------

.. code-block:: php

  $configuration->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/delete-configuration.php>`__

.. note::

  You cannot delete a configuration if it is in use by a running instance.


Listing instances using a configuration
---------------------------------------

You can list all instances using a specific configuration, using its ID,
as shown below:

.. code-block:: php

  $instances = $configuration->instanceList();
  foreach ($instances as $instance) {
      /** @var $instance OpenCloud\Database\Resource\Instance **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/list-configuration-instances.php>`__
