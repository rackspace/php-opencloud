Subnets
=======

Create a subnet
---------------

This operation takes one parameter, an associative array, with the following keys:

+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| Name                  | Description                                                                                                       | Data type                             | Required?   | Default value                                                          | Example value                                                                   |
+=======================+===================================================================================================================+=======================================+=============+========================================================================+=================================================================================+
| ``networkId``         | Network this subnet is associated with                                                                            | String                                | Yes         | -                                                                      | ``eb60583c-57ea-41b9-8d5c-8fab2d22224c``                                        |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``ipVersion``         | IP version                                                                                                        | Integer (``4`` or ``6``)              | Yes         | -                                                                      | ``4``                                                                           |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``cidr``              | CIDR representing the IP address range for this subnet                                                            | String (CIDR)                         | Yes         | -                                                                      | ``192.168.199.0/25``                                                            |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``name``              | A human-readable name for the subnet. This name might not be unique.                                              | String                                | No          | ``null``                                                               | ``My subnet``                                                                   |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``gatewayIp``         | IP address of the default gateway used by devices on this subnet                                                  | String (IP address)                   | No          | First IP address in CIDR                                               | ``192.168.199.128``                                                             |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``dnsNameservers``    | DNS nameservers used by hosts in this subnet                                                                      | Indexed array of strings              | No          | Empty array                                                            | ``array('4.4.4.4', '8.8.8.8')``                                                 |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``allocationPools``   | Subranges of the CIDR available for dynamic allocation to ports                                                   | Indexed array of associative arrays   | No          | Every IP address in CIDR, excluding gateway IP address if configured   | ``array(array('start' => '192.168.199.2', 'end' => '192.168.199.127'))``        |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``hostRoutes``        | Routes that should be used by devices with IP addresses from this subnet (not including the local subnet route)   | Indexed array of associative arrays   | No          | Empty array                                                            | ``array(array('destination' => '1.1.1.0/24', 'nexthop' => '192.168.19.20'))``   |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``enableDhcp``        | Specifies whether DHCP is enabled for this subnet                                                                 | Boolean                               | No          | ``true``                                                               | ``false``                                                                       |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+
| ``tenantId``          | Owner of the subnet. Only admin users can specify a tenant ID other than their own.                               | String                                | No          | Same as tenant creating the subnet                                     | ``123456``                                                                      |
+-----------------------+-------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+------------------------------------------------------------------------+---------------------------------------------------------------------------------+

You can create a subnet as shown in the following example:

.. code-block:: php

  /** @var $subnet OpenCloud\Networking\Resource\Subnet **/
  $subnet = $networkingService->createSubnet(array(
      'name' => 'My subnet',
      'networkId' => 'eb60583c-57ea-41b9-8d5c-8fab2d22224c',
      'ipVersion' => 4,
      'cidr' => '192.168.199.0/25'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/create-subnet.php>`_


Create multiple subnets
-----------------------

This operation takes one parameter, an indexed array. Each element of
this array must be an associative array with the keys shown in `the
preceding table <#create-a-subnet>`__.

You can create multiple subnets as shown in the following example:

.. code-block:: php

  $subnets = $networkingService->createSubnets(array(
      array(
          'name' => 'My subnet #1'
      ),
      array(
          'name' => 'My subnet #2'
      )
  ));

  foreach ($subnets as $subnet) {
      /** @var $subnet OpenCloud\Networking\Resource\Subnet **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/create-subnets.php>`_


List subnets
------------

You can list all the subnets to which you have access as shown in the
following example:

.. code-block:: php

  $subnets = $networkingService->listSubnets();
  foreach ($subnets as $subnet) {
      /** @var $subnet OpenCloud\Networking\Resource\Subnet **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/list-subnets.php>`_


Get a subnet
------------

You can retrieve a specific subnet by using that subnet's ID, as shown
in the following example:

.. code-block:: php

  /** @var $subnet OpenCloud\Networking\Resource\Subnet **/
  $subnet = $networkingService->getSubnet('{subnetId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/get-subnet.php>`_


Update a subnet
---------------

This operation takes one parameter, an associative array, with the
following keys:

+----------------------+------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+----------------------------+---------------------------------------------------------------------------------+
| Name                 | Description                                                                                                      | Data type                             | Required?   | Default value              | Example value                                                                   |
+======================+==================================================================================================================+=======================================+=============+============================+=================================================================================+
| ``name``             | A human-readable name for the subnet. This name might not be unique.                                             | String                                | No          | ``null``                   | ``My updated subnet``                                                           |
+----------------------+------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+----------------------------+---------------------------------------------------------------------------------+
| ``gatewayIp``        | IP address of the default gateway used by devices on this subnet                                                 | String (IP address)                   | No          | First IP address in CIDR   | ``192.168.62.155``                                                              |
+----------------------+------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+----------------------------+---------------------------------------------------------------------------------+
| ``dnsNameservers``   | DNS nameservers used by hosts in this subnet                                                                     | Indexed array of strings              | No          | Empty array                | ``array('4.4.4.4', '8.8.8.8')``                                                 |
+----------------------+------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+----------------------------+---------------------------------------------------------------------------------+
| ``hostRoutes``       | Routes that should be used by devices with IP adresses from this subnet (not including the local subnet route)   | Indexed array of associative arrays   | No          | Empty array                | ``array(array('destination' => '1.1.1.0/24', 'nexthop' => '192.168.17.19'))``   |
+----------------------+------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+----------------------------+---------------------------------------------------------------------------------+
| ``enableDhcp``       | Specifies whether DHCP is enabled for this subnet                                                                | Boolean                               | No          | ``true``                   | ``false``                                                                       |
+----------------------+------------------------------------------------------------------------------------------------------------------+---------------------------------------+-------------+----------------------------+---------------------------------------------------------------------------------+

You can update a subnet as shown in the following example:

.. code-block:: php

  $subnet->update(array(
      'name' => 'My updated subnet',
      'hostRoutes' => array(
          array(
              'destination' => '1.1.1.0/24',
              'nexthop'     => '192.168.17.19'
          )
      ),
      'gatewayIp' => '192.168.62.155'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/update-subnet.php>`_


Delete a subnet
---------------

You can delete a subnet as shown in the following example:

.. code-block:: php

  $subnet->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Networking/delete-subnet.php>`_
