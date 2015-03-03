Complete User Guide for the Networking Service
==============================================

Networking is a service that you can use to create virtual networks and
attach cloud devices such as servers to these networks.

This user guide introduces you the entities in the Networking service —
networks, subnets, and ports — and shows you how to create and manage
these entities.

Table of contents
-----------------

-  `Concepts <#concepts>`__
-  `Prerequisites <#prerequisites>`__

   -  `Client <#client>`__
   -  `Networking service <#networking-service>`__

-  `Networks <#networks>`__

   -  `Create a network <#create-a-network>`__
   -  `Create multiple networks <#create-multiple-networks>`__
   -  `List networks <#list-networks>`__
   -  `Get a network <#get-a-network>`__
   -  `Update a network <#update-a-network>`__
   -  `Delete a network <#delete-a-network>`__

-  `Subnets <#subnets>`__

   -  `Create a subnet <#create-a-subnet>`__
   -  `Create multiple subnets <#create-multiple-subnets>`__
   -  `List subnets <#list-subnets>`__
   -  `Get a subnet <#get-a-subnet>`__
   -  `Update a subnet <#update-a-subnet>`__
   -  `Delete a subnet <#delete-a-subnet>`__

-  `Ports <#ports>`__

   -  `Create a port <#create-a-port>`__
   -  `Create multiple ports <#create-multiple-ports>`__
   -  `List ports <#list-ports>`__
   -  `Get a port <#get-a-port>`__
   -  `Update a port <#update-a-port>`__
   -  `Delete a port <#delete-a-port>`__

Concepts
--------

To use the Networking service effectively, you should understand the
following key concepts:

-  **Network**: An isolated virtual layer-2 broadcast domain that is
   typically reserved for the tenant who created it unless it is
   configured to be shared. The network is the main entity in the
   Networking service. Ports and subnets are always associated with a
   network.

-  **Subnet**: An IP address block that can be used to assign IP
   addresses to virtual instances (such as servers created using the
   Compute service). Each subnet must have a CIDR and must be associated
   with a network.

-  **Port**: A virtual switch port on a logical network switch. Virtual
   instances (such as servers created using the Compute service) attach
   their interfaces into ports. The port also defines the MAC address
   and the IP address or addresses to be assigned to the interfaces
   plugged into them. When IP addresses are associated with a port, this
   also implies the port is associated with a subnet because the IP
   address is taken from the allocation pool for a specific subnet.

Prerequisites
-------------

Client
~~~~~~

To use the Networking service, you must first instantiate a
``OpenStack`` or ``Rackspace`` client object.

-  If you are working with an OpenStack cloud, instantiate an
   ``OpenCloud\OpenStack`` client as follows:

   .. code:: php

       use OpenCloud\OpenStack;

       $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
           'username' => '<YOUR OPENSTACK CLOUD ACCOUNT USERNAME>',
           'password' => '<YOUR OPENSTACK CLOUD ACCOUNT PASSWORD>'
       ));

-  If you are working with the Rackspace cloud, instantiate an
   ``OpenCloud\Rackspace`` client as follows:

   .. code:: php

       use OpenCloud\Rackspace;

       $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
           'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
           'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
       ));

Networking service
~~~~~~~~~~~~~~~~~~

All Networking operations are done via a *networking service object*. To
instantiate this object, call the ``networkingService`` method on the
``$client`` object. This method takes the following arguments:

+------------+-------------------------------------------------------------+-------------+-------------+----------------------------------------------------+---------------------+
| Position   | Description                                                 | Data type   | Required?   | Default value                                      | Example value       |
+============+=============================================================+=============+=============+====================================================+=====================+
| 1          | Name of the service, as it appears in the service catalog   | String      | No          | ``null``; automatically determined when possible   | ``cloudNetworks``   |
+------------+-------------------------------------------------------------+-------------+-------------+----------------------------------------------------+---------------------+
| 2          | Cloud region                                                | String      | Yes         | -                                                  | ``DFW``             |
+------------+-------------------------------------------------------------+-------------+-------------+----------------------------------------------------+---------------------+

.. code:: php

    $region = '<CLOUD REGION NAME>';
    $networkingService = $client->networkingService(null, $region);

Any networks, subnets, and ports created with this
``$networkingService`` instance are stored in the cloud region specified
by ``$region``.

Networks
--------

A network is an isolated virtual layer-2 broadcast domain that is
typically reserved for the tenant who created it unless it is configured
to be shared. The network is the main entity in the Networking service.
Ports and subnets are always associated with a network.

Create a network
~~~~~~~~~~~~~~~~

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

.. code:: php

    $network = $networkingService->createNetwork(array(
        'name' => 'My private backend network'
    ));
    /** @var $network OpenCloud\Networking\Resource\Network **/

[ `Get the executable PHP script for this
example </samples/Networking/create-network.php>`__ ]

Create multiple networks
~~~~~~~~~~~~~~~~~~~~~~~~

This operation takes one parameter, an indexed array. Each element of
this array must be an associative array with the keys shown in `the
preceding table <#create-a-network>`__.

You can create multiple networks as shown in the following example:

.. code:: php

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

[ `Get the executable PHP script for this
example </samples/Networking/create-networks.php>`__ ]

List networks
~~~~~~~~~~~~~

You can list all the networks to which you have access as shown in the
following example:

.. code:: php

    $networks = $networkingService->listNetworks();
    foreach ($networks as $network) {
        /** @var $network OpenCloud\Networking\Resource\Network **/
    }

[ `Get the executable PHP script for this
example </samples/Networking/list-networks.php>`__ ]

Get a network
~~~~~~~~~~~~~

You can retrieve a specific network by using that network's ID, as shown
in the following example:

.. code:: php

    $network = $networkingService->getNetwork('eb60583c-57ea-41b9-8d5c-8fab2d22224c');
    /** @var $network OpenCloud\Networking\Resource\Network **/

[ `Get the executable PHP script for this
example </samples/Networking/get-network.php>`__ ]

Update a network
~~~~~~~~~~~~~~~~

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

.. code:: php

    $network->update(array(
        'name' => 'My updated private backend network'
    ));

[ `Get the executable PHP script for this
example </samples/Networking/update-network.php>`__ ]

Delete a network
~~~~~~~~~~~~~~~~

You can delete a network as shown in the following example:

.. code:: php

    $network->delete();

[ `Get the executable PHP script for this
example </samples/Networking/delete-network.php>`__ ]

Subnets
-------

A subnet represents an IP address block that can be used to assign IP
addresses to virtual instances (such as servers created using the
Compute service). Each subnet must have a CIDR and must be associated
with a network.

Create a subnet
~~~~~~~~~~~~~~~

This operation takes one parameter, an associative array, with the
following keys:

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

.. code:: php

    $subnet = $networkingService->createSubnet(array(
        'name' => 'My subnet',
        'networkId' => 'eb60583c-57ea-41b9-8d5c-8fab2d22224c',
        'ipVersion' => 4,
        'cidr' => '192.168.199.0/25'
    ));
    /** @var $subnet OpenCloud\Networking\Resource\Subnet **/

[ `Get the executable PHP script for this
example </samples/Networking/create-subnet.php>`__ ]

Create multiple subnets
~~~~~~~~~~~~~~~~~~~~~~~

This operation takes one parameter, an indexed array. Each element of
this array must be an associative array with the keys shown in `the
preceding table <#create-a-subnet>`__.

You can create multiple subnets as shown in the following example:

.. code:: php

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

[ `Get the executable PHP script for this
example </samples/Networking/create-subnets.php>`__ ]

List subnets
~~~~~~~~~~~~

You can list all the subnets to which you have access as shown in the
following example:

.. code:: php

    $subnets = $networkingService->listSubnets();
    foreach ($subnets as $subnet) {
        /** @var $subnet OpenCloud\Networking\Resource\Subnet **/
    }

[ `Get the executable PHP script for this
example </samples/Networking/list-subnets.php>`__ ]

Get a subnet
~~~~~~~~~~~~

You can retrieve a specific subnet by using that subnet's ID, as shown
in the following example:

.. code:: php

    $subnet = $networkingService->getSubnet('d3f15879-fb11-49bd-a30b-7704fb98ab1e');
    /** @var $subnet OpenCloud\Networking\Resource\Subnet **/

[ `Get the executable PHP script for this
example </samples/Networking/get-subnet.php>`__ ]

Update a subnet
~~~~~~~~~~~~~~~

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

.. code:: php

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

[ `Get the executable PHP script for this
example </samples/Networking/update-subnet.php>`__ ]

Delete a subnet
~~~~~~~~~~~~~~~

You can delete a subnet as shown in the following example:

.. code:: php

    $subnet->delete();

[ `Get the executable PHP script for this
example </samples/Networking/delete-subnet.php>`__ ]

Ports
-----

A port represents a virtual switch port on a logical network switch.
Virtual instances (such as servers created using the Compute service)
attach their interfaces into ports. The port also defines the MAC
address and the IP address or addresses to be assigned to the interfaces
plugged into them. When IP addresses are associated with a port, this
also implies the port is associated with a subnet because the IP address
is taken from the allocation pool for a specific subnet.

Create a port
~~~~~~~~~~~~~

This operation takes one parameter, an associative array, with the
following keys:

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

.. code:: php

    $port = $networkingService->createPort(array(
        'name' => 'My port',
        'networkId' => 'eb60583c-57ea-41b9-8d5c-8fab2d22224c'
    ));
    /** @var $port OpenCloud\Networking\Resource\Port **/

[ `Get the executable PHP script for this
example </samples/Networking/create-port.php>`__ ]

Create multiple ports
~~~~~~~~~~~~~~~~~~~~~

This operation takes one parameter, an indexed array. Each element of
this array must be an associative array with the keys shown in `the
preceding table <#create-a-port>`__.

You can create multiple ports as shown in the following example:

.. code:: php

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

[ `Get the executable PHP script for this
example </samples/Networking/create-ports.php>`__ ]

List ports
~~~~~~~~~~

You can list all the ports to which you have access as shown in the
following example:

.. code:: php

    $ports = $networkingService->listPorts();
    foreach ($ports as $port) {
        /** @var $port OpenCloud\Networking\Resource\Port **/
    }

[ `Get the executable PHP script for this
example </samples/Networking/list-ports.php>`__ ]

Get a port
~~~~~~~~~~

You can retrieve a specific port by using that port's ID, as shown in
the following example:

.. code:: php

    $port = $networkingService->getPort('75906d20-6625-11e4-9803-0800200c9a66');
    /** @var $port OpenCloud\Networking\Resource\Port **/

[ `Get the executable PHP script for this
example </samples/Networking/get-port.php>`__ ]

Update a port
~~~~~~~~~~~~~

This operation takes one parameter, an associative array, with the
following keys:

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

.. code:: php

    $port->update(array(
        'fixedIps' => array(
            array(
                'subnetId'  => '75906d20-6625-11e4-9803-0800200c9a66',
                'ipAddress' => '192.168.199.59'
            )
        )
    ));

[ `Get the executable PHP script for this
example </samples/Networking/update-port.php>`__ ]

Delete a port
~~~~~~~~~~~~~

You can delete a port as shown in the following example:

.. code:: php

    $port->delete();

[ `Get the executable PHP script for this
example </samples/Networking/delete-port.php>`__ ]
