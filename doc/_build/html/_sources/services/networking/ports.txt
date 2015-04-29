Ports
=====

Create a port
-------------

This operation takes one parameter, an associative array, with the following keys:

+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| Name                 | Description                                                                                 | Data type                                                  | Required?   | Default value                           | Example value                                                                                             |
+======================+=============================================================================================+============================================================+=============+=========================================+===========================================================================================================+
| ``networkId``        | Network this port is associated with                                                        | String                                                     | Yes         | -                                       | ``eb60583c-57ea-41b9-8d5c-8fab2d22224c``                                                                  |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``name``             | A human-readable name for the port. This name might not be unique.                          | String                                                     | No          | ``null``                                | ``My port``                                                                                               |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``adminStateUp``     | The administrative state of port. If ``false`` (down), the port does not forward packets.   | Boolean                                                    | No          | ``true``                                | ``true``                                                                                                  |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``macAddress``       | MAC address to use on this port                                                             | String (MAC address in 6-octet form separated by colons)   | No          | Generated                               | ``0F:5A:6F:70:E9:5C``                                                                                     |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``fixedIps``         | IP addresses for this port                                                                  | Indexed array of associative arrays                        | No          | Automatically allocated from the pool   | ``array(array('subnetId' => '75906d20-6625-11e4-9803-0800200c9a66', 'ipAddress' => '192.168.199.17'))``   |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``deviceId``         | Identifies the device (for example, virtual server) using this port                         | String                                                     | No          | ``null``                                | ``5e3898d7-11be-483e-9732-b2f5eccd2b2e``                                                                  |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``deviceOwner``      | Identifies the entity (for example, DHCP agent) using this port                             | String                                                     | No          | ``null``                                | ``network:router_interface``                                                                              |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``securityGroups``   | Specifies the IDs of any security groups associated with this port                          | Indexed array of strings                                   | No          | Empty array                             | ``array('f0ac4394-7e4a-4409-9701-ba8be283dbc3')``                                                         |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``tenantId``         | Owner of the port. Only admin users can specify a tenant ID other than their own.           | String                                                     | No          | Same as the tenant creating the port    | ``123456``                                                                                                |
+----------------------+---------------------------------------------------------------------------------------------+------------------------------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+

You can create a port as shown in the following example:

.. code-block:: php

  /** @var $port OpenCloud\Networking\Resource\Port **/
  $port = $networkingService->createPort(array(
      'name' => 'My port',
      'networkId' => 'eb60583c-57ea-41b9-8d5c-8fab2d22224c'
  ));


`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/create-port.php>`_


Create multiple ports
---------------------

This operation takes one parameter, an indexed array. Each element of
this array must be an associative array with the keys shown in `the
preceding table <#create-a-port>`__.

You can create multiple ports as shown in the following example:

.. code-block:: php

  $ports = $networkingService->createPorts(array(
      array(
          'name' => 'My port #1',
          'networkId' => 'eb60583c-57ea-41b9-8d5c-8fab2d22224c'
      ),
      array(
          'name' => 'My port #2',
          'networkId' => 'eb60583c-57ea-41b9-8d5c-8fab2d22224c'
      )
  ));

  foreach ($ports as $port) {
      /** @var $port OpenCloud\Networking\Resource\Port **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/create-ports.php>`_


List ports
----------

You can list all the ports to which you have access as shown in the following example:

.. code-block:: php

  $ports = $networkingService->listPorts();

  foreach ($ports as $port) {
      /** @var $port OpenCloud\Networking\Resource\Port **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/list-ports.php>`_


Get a port
----------

You can retrieve a specific port by using that port's ID, as shown in
the following example:

.. code-block:: php

  /** @var $port OpenCloud\Networking\Resource\Port **/
  $port = $networkingService->getPort('{portId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/get-port.php>`_


Update a port
-------------

This operation takes one parameter, an associative array, with the following keys:

+----------------------+---------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| Name                 | Description                                                                                 | Data type                             | Required?   | Default value                           | Example value                                                                                             |
+======================+=============================================================================================+=======================================+=============+=========================================+===========================================================================================================+
| ``name``             | A human-readable name for the port. This name might not be unique.                          | String                                | No          | ``null``                                | ``My port``                                                                                               |
+----------------------+---------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``adminStateUp``     | The administrative state of port. If ``false`` (down), the port does not forward packets.   | Boolean                               | No          | ``true``                                | ``true``                                                                                                  |
+----------------------+---------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``fixedIps``         | IP addresses for this port                                                                  | Indexed array of associative arrays   | No          | Automatically allocated from the pool   | ``array(array('subnetId' => '75906d20-6625-11e4-9803-0800200c9a66', 'ipAddress' => '192.168.199.59'))``   |
+----------------------+---------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``deviceId``         | Identifies the device (for example, virtual server) using this port                         | String                                | No          | ``null``                                | ``5e3898d7-11be-483e-9732-b2f5eccd2b2e``                                                                  |
+----------------------+---------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``deviceOwner``      | Identifies the entity (for example, DHCP agent) using this port                             | String                                | No          | ``null``                                | ``network:router_interface``                                                                              |
+----------------------+---------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+
| ``securityGroups``   | Specifies the IDs of any security groups associated with this port                          | Indexed array of strings              | No          | Empty array                             | ``array('f0ac4394-7e4a-4409-9701-ba8be283dbc3')``                                                         |
+----------------------+---------------------------------------------------------------------------------------------+---------------------------------------+-------------+-----------------------------------------+-----------------------------------------------------------------------------------------------------------+

You can update a port as shown in the following example:

.. code-block:: php

  $port->update(array(
      'fixedIps' => array(
          array(
              'subnetId'  => '75906d20-6625-11e4-9803-0800200c9a66',
              'ipAddress' => '192.168.199.59'
          )
      )
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/update-port.php>`_


Delete a port
-------------

You can delete a port as shown in the following example:

.. code-block:: php

  $port->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/delete-port.php>`_
