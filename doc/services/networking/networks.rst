Networks
========

Create a network
----------------

This operation takes one parameter, an associative array, with the
following keys:

+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+---------------------------------------+----------------------------------+
| Name               | Description                                                                                       | Data type   | Required?   | Default value                         | Example value                    |
+====================+===================================================================================================+=============+=============+=======================================+==================================+
| ``name``           | A human-readable name for the network. This name might not be unique.                             | String      | No          | ``null``                              | ``My private backend network``   |
+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+---------------------------------------+----------------------------------+
| ``adminStateUp``   | The administrative state of network. If ``false`` (down), the network does not forward packets.   | Boolean     | No          | ``true``                              | ``true``                         |
+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+---------------------------------------+----------------------------------+
| ``shared``         | Specifies whether the network resource can be accessed by any tenant.                             | Boolean     | No          | ``false``                             | ``false``                        |
+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+---------------------------------------+----------------------------------+
| ``tenantId``       | Owner of network. Only admin users can specify a tenant ID other than their own.                  | String      | No          | Same as tenant creating the network   | ``123456``                       |
+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+---------------------------------------+----------------------------------+

You can create a network as shown in the following example:

.. code-block:: php

  /** @var $network OpenCloud\Networking\Resource\Network **/
  $network = $networkingService->createNetwork(array(
      'name' => 'My private backend network'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/create-network.php>`__


Create multiple networks
------------------------

This operation takes one parameter, an indexed array. Each element of
this array must be an associative array with the keys shown in `the
preceding table <#create-a-network>`__.

You can create multiple networks as shown in the following example:

.. code-block:: php

  $networks = $networkingService->createNetworks(array(
      array(
          'name' => 'My private backend network #1'
      ),
      array(
          'name' => 'My private backend network #2'
      )
  ));

  foreach ($networks as $network) {
      /** @var $network OpenCloud\Networking\Resource\Network **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/create-networks.php>`_

List networks
-------------

You can list all the networks to which you have access as shown in the
following example:

.. code-block:: php

  $networks = $networkingService->listNetworks();

  foreach ($networks as $network) {
      /** @var $network OpenCloud\Networking\Resource\Network **/
  }


`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/list-networks.php>`_


Get a network
-------------

You can retrieve a specific network by using that network's ID, as shown
in the following example:

.. code-block:: php

  /** @var $network OpenCloud\Networking\Resource\Network **/
  $network = $networkingService->getNetwork('{networkId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/get-network.php>`_


Update a network
----------------

This operation takes one parameter, an associative array, with the
following keys:

+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+-----------------+------------------------------------------+
| Name               | Description                                                                                       | Data type   | Required?   | Default value   | Example value                            |
+====================+===================================================================================================+=============+=============+=================+==========================================+
| ``name``           | A human-readable name for the network. This name might not be unique.                             | String      | No          | ``null``        | ``My updated private backend network``   |
+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+-----------------+------------------------------------------+
| ``adminStateUp``   | The administrative state of network. If ``false`` (down), the network does not forward packets.   | Boolean     | No          | ``true``        | ``true``                                 |
+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+-----------------+------------------------------------------+
| ``shared``         | Specifies whether the network resource can be accessed by any tenant.                             | Boolean     | No          | ``false``       | ``false``                                |
+--------------------+---------------------------------------------------------------------------------------------------+-------------+-------------+-----------------+------------------------------------------+

You can update a network as shown in the following example:

.. code-block:: php

  $network->update(array(
      'name' => 'My updated private backend network'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/update-network.php>`_


Delete a network
~~~~~~~~~~~~~~~~

You can delete a network as shown in the following example:

.. code-block:: php

  $network->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/delete-network.php>`_
